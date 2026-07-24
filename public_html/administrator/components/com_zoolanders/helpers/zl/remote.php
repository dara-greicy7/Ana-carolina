<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class zlHelperRemote extends AppHelper
{
	/**
	 * Cache object
	 */
	protected $cache;

	/**
	* Error
	*/
	protected $_error = '';

	/**
	* Get server call url
	 *
	 * @return string
	 */
	public function getServerCallURL($task, $args = array())
	{
		$host = 'https://www.zoolanders.com';
		$args = array(
			'option' => 'com_zoo',
			'format' => 'raw',
			'controller' => 'zlmanager',
			'task' => $task
		) + $args;

		return $host . '/index.php?' . http_build_query($args);
	}

	/**
	 * Execute a call to the server
	 *
	 * @param string $task
	 * @param bool $auth
	 *
	 * @return mixed false if error
	 */
	public function callServer($task, $args = array())
	{
		$http 	  = \JHttpFactory::getHttp();
		$url 	  = $this->getServerCallURL($task, $args);
		$response = $this->app->zl->response->create();

		try {
			$http_response = $http->get($url);
		} catch (\RuntimeException $e) {
			$http_response = null;
			$http_error	= $e->getMessage();
		}

		if ($http_response) {
			$response->code = $http_response->code;
			$response->data = json_decode($http_response->body, true);
		} else {
			$response->set('message', $http_error)->code = 500;
		}

		return $response;
	}

	/**
	 * Downloads from a URL and saves the result as a local file
	 *
	 * @param string $url
	 * @param path $target
	 *
	 * @return array $response
	 */
	protected function _download($url, $target = true)
	{
		// check tmp permissions
		if ($warning = $this->app->zlfw->path->checkSystemPaths()) {
			$this->setError($warning);
			return false;
		}

		// capture PHP errors
		$track_errors = ini_get('track_errors');
		ini_set('track_errors', true);

		// set user agent
		$version = new \JVersion;
		ini_set('user_agent', $version->getUserAgent('Installer'));

		$http = \JHttpFactory::getHttp();

		try {
			$http_response = $http->get($url);
		} catch (\Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		if (302 === $http_response->code && isset($http_response->headers['Location'])) {
			return self::_download($http_response->headers['Location'], $target);
		} else if (200 !== $http_response->code) {
			$data = json_decode($http_response->body);
			$this->setError($data->message
				? $data->message
				: 'Download failed with unknown error'
			);
			return false;
		} else if ($http_response->body === '') {
			$this->setError('The download content is empty');
			return false;
		}

		if ($target) {

			// parse the Content-Disposition header to get the file name
			if (isset($http_response->headers['Content-Disposition']) &&
				preg_match(
					'/filename\s?=\s?([^;=\n]*)/',
					$http_response->headers['Content-Disposition'],
					$parts
				)
			) {
				$target = trim($parts[1], '"');
			}

			$target = $this->app->path->path('tmp:') . '/' . basename($target);

			// write buffer to file
			file_put_contents($target, $http_response->body);

			// check file
			if (filesize($target) < 0) {
				$this->setError('Download failed');
				return false;
			}

			$result = $target;

		} else {
			$result = $http_response->body;
		}

		// restore error tracking to what it was before
		ini_set('track_errors', $track_errors);

		// bump the max execution time because not using built in php zip libs are slow
		@set_time_limit(ini_get('max_execution_time'));

		return $result;
	}

	/**
	 * Installs the downloaded package
	 *
	 * @param string $file Path to the download package
	 *
	 * @return mixed Result string on success, false on failure
	 */
	public function install($file)
	{
		// check tmp permissions
		if ($warning = $this->app->zlfw->path->checkSystemPaths()) {
			$this->setError($warning);
			return false;
		}

		jimport('joomla.installer.installer');
		jimport('joomla.installer.helper');
		jimport('joomla.filesystem.archive');

		$lang = JFactory::getLanguage();
		$lang->load(strtolower('com_installer'), JPATH_ADMINISTRATOR, null, false, false) ||
		$lang->load(strtolower('com_installer'), JPATH_ADMINISTRATOR, $lang->getDefault(), false, false);

		// unpack the package
		$dir = $this->app->path->path('tmp:') . '/' . uniqid('zoolanders_');
		if (!\JArchive::extract($file, $dir)) {
			$this->setError('File extraction failed');
			return false;
		}

		// get installer instance
		$installer = \JInstaller::getInstance();

		// get the package manifest
		$xmls = \JFolder::files($dir, '.xml$', 1, true);
		if (count($xmls)) {
			// it's an app?
			$app_xml = $dir . '/application.xml';
			if (\JFile::exists($app_xml)) {

				if (!$this->_installApp($app_xml, $dir)) {
					return false;
				}

			// or standard package
			} else if (!$installer->install($dir)) {
				$msgs = array();
				foreach (\JFactory::getApplication()->getMessageQueue() as $msg) {
					$msgs[] = $msg['message'];
				}
				$this->setError(implode("\n", $msgs));
				return false;
			}

		// no xml, probably pack of packages
		} else {

			$zips = \JFolder::files($dir, '.zip', 1, true);
			if (count($zips)) {
				foreach ($zips as $zip) {
					if (!$this->install($zip)) {
						return false;
					}
				}
			}
		}

		// cleanup temporal files
		\JInstallerHelper::cleanupInstall($file, $dir);

		return true;
	}

	private function _installApp($xml, $dir)
	{
		$xml = simplexml_load_file($xml);

		if (!$xml || $xml->getName() !== 'application') {
			return false;
		}

		$group = (string) $xml->group;
		$dest = JPATH_SITE . '/media/zoo/applications/' . $group . '/';
		$dir = rtrim($dir, "\\/") . '/';

		// if updating, do not overwrite config/positions/metadata
		if (\JFolder::exists($dest))
		{
			foreach ($this->app->filesystem->readDirectoryFiles(
				$dir . 'types/', '', '/\.config$/', false
			) as $file) {
				if (\JFile::exists($dest . 'types/' . $file)) {
					\JFile::delete($dir . 'types/' . $file);
				}
			}

			foreach ($this->app->filesystem->readDirectoryFiles(
				$dir, '', '/(positions\.(config|xml)|metadata\.xml)$/', true
			) as $file) {
				if (\JFile::exists($dest . $file)) {
					\JFile::delete($dir . $file);
				}
			}
		}

		return \JFolder::copy($dir, $dest, '', true);
	}

	/**
	 * Download and install an Add-on
	 *
	 * @return void
	 */
	public function downloadAndInstall($packages)
	{
		$packages = (array) $packages;

		if (!$download = $this->download($packages)) {
			return false;
		}

		if (!$this->install($download)) {
			return false;
		}

		return true;
	}

	/**
	 * Uninstall an extension
	 *
	 * @param string|array $name The extension/s name formated as "type_name"
	 *
	 * @return mixed Result string on success, false on failure
	 */
	public function uninstall($extensions)
	{
		$extensions = (array) $extensions;

		if (empty($extensions)) {
			$this->setError('No extensions provided');
			return false;
		}

		// process joomla native extensions
		$query = "SELECT * FROM `#__extensions`" .
			" WHERE `name` IN ('" . implode("','", $extensions) . "')" .
			" AND (type = 'plugin' OR type = 'module' OR type = 'component' OR type = 'file')" .
			" AND state != -1";

		if ($list = $this->app->database->queryObjectList($query)) {
			require_once JPATH_ADMINISTRATOR . '/components/com_installer/models/manage.php';
			$installer = \JModelLegacy::getInstance('Manage', 'InstallerModel');

			foreach ($list as $ext) {
				if (!$installer->remove((array) $ext->extension_id)) {
					$msgs = array();
					foreach (\JFactory::getApplication()->getMessageQueue() as $msg) {
						$msgs[] = $msg['message'];
					}
					$this->setError(implode("\n", $msgs));
					return false;
				}
			}
		}

		// process ZOO elements
		$paths = array();
		$els_path = $this->app->path->path('root:plugins/system/zoo_zlelements/zoo_zlelements/elements');

		foreach ($extensions as $ext) {
			// workaround for Texts elements
			if ($ext == 'texts') {
				$paths[] = "${els_path}/textareapro";
				$paths[] = "${els_path}/textpro";
			} else {
				$paths[] = "${els_path}/${ext}";
			}

			foreach ($paths as $path) {
				if (is_dir($path) && $path !== $els_path) {
					JFolder::delete($path);
				}
			}
		}

		// process ZOO Apps
		foreach ($extensions as $ext)
		{
			$app_group = $ext;
			$db = $this->app->database;

			// check if there are App instances created, if so, abort
			$query = "SELECT * FROM `#__zoo_application`"
				. " WHERE `application_group` = " . $db->quote($app_group);
			$result = $db->queryObjectList($query);

			if ($result !== false && !empty($result)) {
				$this->setError(
					JText::sprintf('PLG_ZLFRAMEWORK_EXT_REMANING_APPS', ucfirst($ext))
				);
			} else {

				// create application object
				$application = $this->app->object->create('Application');
				$application->setGroup($app_group);

				try {
					$this->app->install->uninstallApplication($application);
				} catch (\InstallHelperException $e) {
					$this->setError(
						JText::sprintf('Error uninstalling application group (%s).', $e)
					);
				}
			}
		}

		return true;
	}

	/**
	 * Init the cache object
	 *
	 * @return mixed
	 */
	public function initCache($name)
	{
		$cache = $this->app->cache->create(
			$this->app->path->path('root:cache') . "/com_zoolanders/admin-manager/${name}",
			true,
			604800
		);

		return ($cache) && $cache->check() ? $cache : false;
	}

	/**
	 * Stores an Error
	 */
	public function setError($error)
	{
		$this->_error = $error;
	}

	/**
	 * Get errors
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * Merge array by value match
	 *
	 * @return string
	 */
	public function mergeLists($arrayA, $arrayB, $columnKey = '')
	{
		foreach ($arrayB as $ins) {
			$key = array_search($ins[$columnKey], $this->array_column($arrayA, $columnKey));
			if ($key !== false) {
				$arrayA[$key] = array_merge($arrayA[$key], $ins);
			}
		}

		return $arrayA;
	}

	/**
	 * This is part of the array_column library
	 *
	 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
	 * @license http://opensource.org/licenses/MIT MIT
	 *
	 * Returns the values from a single column of the input array, identified by
	 * the $columnKey.
	 *
	 * Optionally, you may provide an $indexKey to index the values in the returned
	 * array by the values from the $indexKey column in the input array.
	 *
	 * @param array $input A multi-dimensional array (record set) from which to pull
	 *					 a column of values.
	 * @param mixed $columnKey The column of values to return. This value may be the
	 *						 integer key of the column you wish to retrieve, or it
	 *						 may be the string key name for an associative array.
	 * @param mixed $indexKey (Optional.) The column to use as the index/keys for
	 *						the returned array. This value may be the integer key
	 *						of the column, or it may be the string key name.
	 * @return array
	 */
	protected function array_column($input = null, $columnKey = null, $indexKey = null)
	{
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc = func_num_args();
		$params = func_get_args();

		if ($argc < 2) {
			trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
			return null;
		}

		if (!is_array($params[0])) {
			trigger_error(
				'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
				E_USER_WARNING
			);
			return null;
		}

		if (!is_int($params[1])
			&& !is_float($params[1])
			&& !is_string($params[1])
			&& $params[1] !== null
			&& !(is_object($params[1]) && method_exists($params[1], '__toString'))
		) {
			trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
			return false;
		}

		if (isset($params[2])
			&& !is_int($params[2])
			&& !is_float($params[2])
			&& !is_string($params[2])
			&& !(is_object($params[2]) && method_exists($params[2], '__toString'))
		) {
			trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
			return false;
		}

		$paramsInput = $params[0];
		$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

		$paramsIndexKey = null;
		if (isset($params[2])) {
			if (is_float($params[2]) || is_int($params[2])) {
				$paramsIndexKey = (int) $params[2];
			} else {
				$paramsIndexKey = (string) $params[2];
			}
		}

		$resultArray = array();

		foreach ($paramsInput as $row) {
			$key = $value = null;
			$keySet = $valueSet = false;

			if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
				$keySet = true;
				$key = (string) $row[$paramsIndexKey];
			}

			if ($paramsColumnKey === null) {
				$valueSet = true;
				$value = $row;
			} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
				$valueSet = true;
				$value = $row[$paramsColumnKey];
			}

			if ($valueSet) {
				if ($keySet) {
					$resultArray[$key] = $value;
				} else {
					$resultArray[] = $value;
				}
			}

		}

		return $resultArray;
	}
}
