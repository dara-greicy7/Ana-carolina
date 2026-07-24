<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

App::getInstance('zoo')->loader->register('zlHelperRemote', 'helpers:zl/remote.php');

class zlHelperExtensions extends zlHelperRemote
{
	private $extensions = array();

	public function __construct($app)
	{
		// call parent constructor
		parent::__construct($app);

		// save local static extensions list
		$this->extensions = json_decode(file_get_contents(
			$this->app->path->path('zlmedia:apps/manager/extensions.json')
		), true);

		$this->cache = $this->initCache('extensions');
	}

	/**
	 * Get server call url
	 *
	 * @return string
	 */
	public function getServerCallURL($task, $args = array())
	{
		// add auth if fulfilled
		$auth = $this->getCredentials();
		if (!empty($auth['username']) && !empty($auth['password'])) {
			$args = $args + $auth;
		}

		return parent::getServerCallURL($task, $args);
	}

	/**
	 * Retrieve the updated extensions list
	 */
	public function retrieve($force = false)
	{
		$retrieved = $this->getRetrievedCache();

		if ($force || empty($retrieved)) {

			$response = $this->callServer('retrieveExtensions');
			if ($response->code !== 200) {
				$this->setError($response->message);
				return false;
			}

			$retrieved = $response->data;
			if ($this->cache) {
				$this->cache->set(md5(serialize($this->getCredentials())), $retrieved)->save();
			}
		}

		return $retrieved;
	}

	/**
	 * Get extensions list
	 *
	 * @return array Array
	 */
	public function getExtensions()
	{
		$extensions = $this->extensions;

		// get and merge installed extensions
		$extensions = $this->mergeLists($extensions, $this->getInstalled(), 'name');

		return $extensions;
	}

	/**
	 * Get extensions installed state list
	 *
	 * @return array Array
	 */
	public function getInstalled()
	{
		$installed = array();

		// zooitempro workaround
		$this->extensions[] = array('name' => 'zooitem pro');

		/* PLUGINS/MODULES */
		$query = "SELECT * FROM `#__extensions`" .
			" WHERE `name` IN ('" . implode("','", $this->array_column($this->extensions, 'name')) . "')" .
			" AND (type = 'plugin' OR type = 'module' OR type = 'component')" .
			" AND state != -1";

		foreach ($this->app->database->queryObjectList($query) as $item) {
			if (strlen($item->manifest_cache) && $data = json_decode($item->manifest_cache)) {
				$installed[] = array(
					'name' => strtolower(str_replace(' ', '', $item->name)),
					'installed' => true,
					'installedVersion' => $data->version,
					'enabled' => (Bool) $item->enabled
				);
			}
		}

		/* ELEMENTS */
		foreach ($this->app->path->dirs('plugins:system/zoo_zlelements/zoo_zlelements/elements') as $type) {
			if (is_file($this->app->path->path("elements:$type/$type.php"))) {
				if ($element = $this->app->element->create($type)) {
					if ($element->getMetaData('hidden') != 'true') {
						$installed[] = array(
							'name' => strtolower(str_replace(' ', '', $element->getMetaData('name'))),
							'installed' => true,
							'installedVersion' => $element->getMetaData('version'),
							'enabled' => true
						);
					}
				}
			}
		}

		// texts workaround
		$installedTexts = array_filter($installed, function ($ext) { return in_array($ext['name'], array('textpro', 'textareapro')); });

		if (count($installedTexts) == count(array('textpro', 'textareapro'))) {
			$texts = array_shift($installedTexts);
			$texts['name'] = 'texts';
			$installed[] = $texts;
		}

		/* APPS */
		foreach ($this->app->zoo->getApplicationGroups() as $app) {
			$author = strtolower($app->getMetaData('author'));
			if ($author == 'zoolanders' || $author == 'joolanders') {
				$installed[] = array(
					'name' => strtolower(str_replace(' ', '', $app->getMetaData('name'))),
					'installed' => true,
					'installedVersion' => $app->getMetaData('version'),
					'enabled' => true
				);
			}
		}

		return $installed;
	}

	/**
	 * Download extensions packages
	 *
	 * @return array $response
	 */
	public function download($extensions)
	{
		return parent::_download($this->getServerCallURL('getExtensionPackage',
			compact('extensions') + $this->getCredentials()
		));
	}

	/**
	 * Toggle the Extensions status
	 *
	 * @param array $extensions Array of extensions to toggle
	 */
	public function toggle($extensions)
	{
		$query = "SELECT * FROM `#__extensions`" .
			" WHERE `name` IN ('" . implode("','", $extensions) . "')" .
			" AND type = 'plugin'" .
			" AND state != -1";

		foreach ($this->app->database->queryObjectList($query) as $ext) {
			$state = (int) !$ext->enabled;
			$result = $this->app->database->setQuery("
				UPDATE `#__extensions`
				SET `enabled` = {$state}
				WHERE `extension_id` = '{$ext->extension_id}'
			")->execute();

			if (!$result) {
				$this->setError('Something went wrong');
				return false;
			}
		}

		return true;
	}

	public function enable($extensions)
	{
		$query = "SELECT * FROM `#__extensions`" .
			" WHERE `name` IN ('" . implode("','", $extensions) . "')" .
			" AND type = 'plugin'" .
			" AND state != -1";

		foreach ($this->app->database->queryObjectList($query) as $ext) {
			$result = $this->app->database->setQuery("
				UPDATE `#__extensions`
				SET `enabled` = 1
				WHERE `extension_id` = '{$ext->extension_id}'
			")->execute();

			if (!$result) {
				$this->setError('Something went wrong');
				return false;
			}
		}

		return true;
	}

	public function disable($extensions)
	{
		$query = "SELECT * FROM `#__extensions`" .
			" WHERE `name` IN ('" . implode("','", $extensions) . "')" .
			" AND type = 'plugin'" .
			" AND state != -1";

		foreach ($this->app->database->queryObjectList($query) as $ext) {
			$result = $this->app->database->setQuery("
				UPDATE `#__extensions`
				SET `enabled` = 0
				WHERE `extension_id` = '{$ext->extension_id}'
			")->execute();

			if (!$result) {
				$this->setError('Something went wrong');
				return false;
			}
		}

		return true;
	}

	/**
	 * Get cached extensions list
	 *
	 * @return mixed
	 */
	public function getRetrievedCache()
	{
		$extensions = array();

		if ($this->cache && $json = $this->cache->get(md5(serialize($this->getCredentials())))) {
			$extensions = json_decode(json_encode($json), true);
		}

		return $extensions;
	}

	/**
	 * Get extension cached value
	 *
	 * @return array
	 */
	public function getRetrievedExtension($name)
	{
		if ($extensions = $this->getRetrievedCache()) {
			$key = array_search($name, $this->array_column($extensions, 'name'));
			return $extensions[$key];
		}
		$this->setError('Extension not found');
		return false;
	}

	/**
	 * Get credentials params
	 *
	 * @return string The url encoded credentials
	 */
	public function getCredentials()
	{
		$settings = $this->app->zl->getConfig('admin-auth');
		$username = $settings->get('username', '');
		$password = $settings->get('password', '');
		$password = $password ? $this->app->zlfw->crypt($password, 'decrypt') : '';

		return array(
			'username' => urlencode($username),
			'password' => urlencode($password)
		);
	}
}
