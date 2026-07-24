<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class plgSystemZoo_zlelementsInstallerScript
{
	protected $_error;
	protected $_src;
	protected $_ext = 'zoo_zlelements';
	protected $_ext_name = 'ZL Elements';
	protected $_lng_prefix = 'PLG_ZLELEMENTS_SYS';
	protected $_plugin_updated = true;

	/* List of obsolete files and folders */
	protected $_obsolete = array(
		'files'	=> array(
			'plugins/system/zoo_zlelements/zoo_zlelements/control.json',
			'plugins/system/zoo_zlelements/zoo_zlelements/helpers/zlhelper.php'
		),
		'folders' => array(
			'plugins/system/zoo_zlelements/zoo_zlelements/languages'
		)
	);

	/**
	 * Called before any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install)
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, $parent)
	{
		// init vars
		$db = JFactory::getDBO();
		$type = strtolower($type);
		$this->_src = $parent->getParent()->getPath('source'); // tmp folder
		$this->_target = JPATH_ROOT.'/plugins/system/zoo_zlelements'; // install folder
		$html = '';

		// load ZLFW sys language file
		JFactory::getLanguage()->load('plg_system_zlframework.sys', JPATH_ADMINISTRATOR, 'en-GB', true);

		// check dependencies if not uninstalling
		if($type != 'uninstall' && !$this->checkRequirements($parent)){
			Jerror::raiseWarning(null, $this->_error);
			return false;
		}

		if($type == 'install' || $type == 'update')
		{
			if($type == 'update')
			{
				$cur_manifest = simplexml_load_file(JPATH_ROOT.'/plugins/system/zoo_zlelements/zoo_zlelements.xml');
				$new_manifest = $parent->get("manifest");

				// if trying to update with older plugin, just update the elements
				if( version_compare((string)$cur_manifest->version, (string)$new_manifest->version, '>') ) {

					// set the plugin update state
					$this->_plugin_updated = false;

					// copy installed plugin files into temp folder to be able to install without issues
					JFile::copy(
						JPath::clean(JPATH_ROOT.'/plugins/system/zoo_zlelements/zoo_zlelements.xml'),
						JPath::clean("{$this->_src}/zoo_zlelements.xml")
					);
					JFile::copy(
						JPath::clean(JPATH_ROOT.'/plugins/system/zoo_zlelements/zoo_zlelements.php'),
						JPath::clean("{$this->_src}/zoo_zlelements.php")
					);
					if (!JFolder::exists("{$this->_src}/languages")) {
						JFolder::create("{$this->_src}/languages");
					}
					JFile::copy(
						JPath::clean(JPATH_ROOT.'/administrator/language/en-GB/en-GB.plg_system_zoo_zlelements.ini'),
						JPath::clean("{$this->_src}/languages/en-GB.plg_system_zoo_zlelements.ini")
					);

					$html .= '<p>'.JText::_($this->langString('_ALLREADY_UP_TO_DATE')).'</p>';
				} else {
					// Remove depricated folders
					$f = JPATH_ROOT.'/plugins/system/zoo_zlelements/zoo_zlelements/fields'; // even if removed will be repopulated with the new content
					if(JFolder::exists($f))	JFolder::delete($f);
				}
			}

			// make sure elements folder exist
			if (!JFolder::exists("{$this->_src}/zoo_zlelements")) {
				JFolder::create("{$this->_src}/zoo_zlelements");
			}

			// element tasks
			if (JFolder::exists("{$this->_src}/elements"))
			{

				// install lang files to admin folder
				foreach(JFolder::folders("{$this->_src}/elements") as $element){

					if(JFolder::exists("{$this->_src}/elements/$element/language")){

						if (JFolder::copy(
							"{$this->_src}/elements/$element/language",
							JPATH_ADMINISTRATOR."/language",
							'', // empty base path
							true // force overwrite
						)) {
							// we can't use the move function as it's not working on windows servers
							JFolder::delete("{$this->_src}/elements/$element/language");
						}
					}

					// remove old lang file
					if (JFile::exists("{$this->_target}/zoo_zlelements/language/en-GB/en-GB.plg_system_zoo_zlelements_$element.ini")) {
						JFile::delete("{$this->_target}/zoo_zlelements/language/en-GB/en-GB.plg_system_zoo_zlelements_$element.ini");
					}
				}

				// move all languages files to the new admin location
				if(JFolder::exists("{$this->_src}/elements/$element/language")){
					if (JFolder::copy(
						"{$this->_target}/zoo_zlelements/language",
						JPATH_ADMINISTRATOR."/language",
						'', // empty base path
						true // force overwrite
					)) {
						// we can't use the move function as it's not working on windows servers
						JFolder::delete("{$this->_target}/zoo_zlelements/language");
					}
				}


				// move elements to install folder
				if (JFolder::exists("{$this->_src}/elements")) {

					// move the elements, using copy instead of move
					if (JFolder::copy(
						'elements',
						'zoo_zlelements/elements',
						$this->_src
					)) {
						// we can't use the move function as it's not working on windows servers
						JFolder::delete("{$this->_src}/elements");
					}
				}

				// execute individual elements install scripts
				foreach(JFolder::Folders("{$this->_src}/zoo_zlelements/elements") as $element) {
					$file = "{$this->_src}/zoo_zlelements/elements/$element/install.script.php";
					if(is_file($file)){
						ob_start();
						include($file);
						$html .= ob_get_contents();
						ob_end_clean();
					}
				}
			}
		}

		// render message
		echo $html;
	}

	/**
	 * Called on installation
	 *
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install($parent)
	{
		// init vars
		$db = JFactory::getDBO();       
    }

    /**
	 * Called on uninstallation
	 *
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function uninstall($parent)
	{
		// execute individual elements uninstall scripts
		$els_path = JPATH_ROOT.'/plugins/system/zoo_zlelements/zoo_zlelements/elements';
		if(JFolder::exists($els_path) && count(JFolder::folders($els_path)) > 0) {
			foreach(JFolder::folders($els_path) as $element) {
				$file = JPATH_ROOT."/plugins/system/zoo_zlelements/zoo_zlelements/elements/$element/install.script.php";
				if(is_file($file)){
					ob_start();
					include($file);
					$html .= ob_get_contents();
					ob_end_clean();
				}
			}
			echo $html;
		};
		

		// enqueue Message
        JFactory::getApplication()->enqueueMessage(JText::_($this->langString('_UNINSTALL')));
    }

	/**
	 * Called after install
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install)
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, $parent)
	{
		// init vars
		$db = JFactory::getDBO();
		$type = strtolower($type);
		$release = $parent->get( "manifest" )->version;

		if($type == 'install'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_INSTALL', $this->_ext_name, $release);
		}

		// show the update message if the plugin was actually updated
		if($type == 'update' && $this->_plugin_updated){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_UPDATE', $this->_ext_name, $release);
		}

		// enable plugin
		$db->setQuery("UPDATE `#__extensions` SET `enabled` = 1 WHERE `type` = 'plugin' AND `element` = '{$this->_ext}' AND `folder` = 'system'")->execute();

		// remove obsolete
		$this->removeObsolete();
	}

	/**
	 * Removes obsolete files and folders
	 * @version 1.1
	 */
	protected function removeObsolete()
	{
		// Remove files
		if(!empty($this->_obsolete['files'])) foreach($this->_obsolete['files'] as $file) {
			$f = JPATH_ROOT.'/'.$file;
			if(!JFile::exists($f)) continue;
			JFile::delete($f);
		}

		// Remove folders
		if(!empty($this->_obsolete['folders'])) foreach($this->_obsolete['folders'] as $folder) {
			$f = JPATH_ROOT.'/'.$folder;
			if(!JFolder::exists($f)) continue;
			JFolder::delete($f);
		}
	}

	/**
	 * creates the lang string
	 * @version 1.0
	 *
	 * @return  string
	 */
	protected function langString($string)
	{
		return $this->_lng_prefix.$string;
	}

	/**
	 * check requirements
	 *
	 * @return  boolean  True on success
	 */
	protected function checkRequirements($parent)
	{
		/*
		 * make sure ZLFW is up to date
		 */
		if($min_zlfw_release = $parent->get( "manifest" )->attributes()->zlfw)
		{
			$zlfw_manifest = simplexml_load_file(JPATH_ROOT.'/plugins/system/zlframework/zlframework.xml');

			if( version_compare((string)$zlfw_manifest->version, (string)$min_zlfw_release, '<') ) {
				$this->_error = "<a href=\"https://www.zoolanders.com/extensions/zl-framework\" target=\"_blank\">ZL Framework</a> v{$min_zlfw_release} or higher required, please update it and retry the installation.";
				return false;
			}
		}

		return true;
	}
}