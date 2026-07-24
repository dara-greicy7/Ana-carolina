<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

App::getInstance('zoo')->loader->register('zlHelperRemote', 'helpers:zl/remote.php');

/**
 * Class zlHelperTransifex
 */
class zlHelperTransifex extends zlHelperRemote
{
	/*
	   Function: Constructor
	*/
	public function __construct($app)
	{
		// call parent constructor
		parent::__construct($app);

		$this->cache = $this->initCache('languages');
	}

	/*
		Retrieve languages list
	*/
	public function retrieve($force = false)
	{
		if ($this->cache) {
			$retrieved = $this->cache->get('languages');
		}

		if ($force || empty($retrieved)) {

			$response = $this->callServer('retrieveLanguages');
			if ($response->code !== 200) {
				$this->setError($response->message);
				return false;
			}

			$retrieved = $response->data;
			if ($this->cache) {
				$this->cache->set('languages', $retrieved)->save();
			}
		}

		return $retrieved;
	}

	/*
		Get languages list
	*/
	public function getLanguages()
	{
		$jlanguages = JFactory::getLanguage()->getKnownLanguages(JPATH_SITE);
		unset($jlanguages['en-GB']);

		$languages = array();
		foreach ($jlanguages as $language) {
			$languages[] = array(
				'code' => $this->deformatISO($language['tag']),
				'name' => $language['name']
			);
		}

		// get and merge installed language packages
		$languages = $this->mergeLists($languages, $this->getInstalled(), 'code');

		return $languages;
	}

	/**
	 * Get extensions installed state list
	 *
	 * @return array Array
	 */
	public function getInstalled()
	{
		$query = "SELECT * FROM `#__extensions`" .
			" WHERE `name` LIKE ('%zoolanders_language_pack%')" .
			" AND (type = 'file')" .
			" AND state != -1";

		$installed = array();
		foreach ($this->app->database->queryObjectList($query) as $item) {
			if (strlen($item->manifest_cache) && $data = json_decode($item->manifest_cache)) {
				$installed[] = array(
					'code' => $this->deformatISO(substr($item->name, 0, 5)),
					'installed' => (int) $data->version
				);
			}
		}

		return $installed;
	}

	/*
		Download Language Package
	*/
	public function download($language)
	{
		return parent::_download($this->getServerCallURL('getLanguagePackage', array(
			'language' => array_shift($language)
		)));
	}

	/**
	 * Uninstall an extension
	 */
	public function uninstall($languages)
	{
		// set the package name
		foreach ($languages as &$language) {
			$language = $this->formatISO($language) . '.zoolanders_language_pack';
		}

		return parent::uninstall($languages);
	}

	/*
		Function: formatISO
	*/
	public function formatISO($code)
	{
		if (stripos($code, '_') !== false){
			return str_replace('_', '-', $code);
		}

		if ($code === 'en') {
			return 'en-GB';
		}

		return sprintf('%s-%s', $code, strtoupper($code));
	}

	/*
		Function: deformatISO

		Convert tag to transifex lang code,
		which is 'es' instead of 'es-ES'
		or 'es-MX' when location is different
	*/
	public function deformatISO($iso)
	{
		// workaround for Catalan
		if ($iso === 'ca-ES') {
			return 'ca';
		}

		$parts = explode('-', $iso);
		return strtolower($parts[0]) === strtolower($parts[1])
			? strtolower($parts[0])
			: $iso;
	}
}
