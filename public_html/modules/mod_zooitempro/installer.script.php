<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class mod_zooitemproInstallerScript
{
	protected $_error;
	protected $_src;
	protected $_target;
	protected $_ext = 'mod_zooitempro';
	protected $_ext_name = 'Module ZOOitem Pro';
	protected $_lng_prefix = 'MOD_ZOOITEMPRO_SYS';

	/* List of obsolete files and folders */
	protected $_obsolete = array(
		'files'	=> array(
		),
		'folders' => array(
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
		$this->_target = JPATH_ROOT.'/modules/mod_zooitempro'; // install folder

		// load ZLFW language file
		JFactory::getLanguage()->load('plg_system_zlframework.sys', JPATH_ADMINISTRATOR, 'en-GB', true);

		// check dependencies if not uninstalling
		if($type != 'uninstall' && !$this->checkDependencies($parent)){
			Jerror::raiseWarning(null, $this->_error);
			return false;
		}

		if ($type == 'update') {

			/*
			 * when updating we don't wont to override renderer/item folder,
			 * so let's delete the temp folder before install only if it already exists
			 */
			if (JFolder::exists($this->_target.'/renderer/item') 
					&& JFolder::exists($this->_src.'/renderer/item'))
				JFolder::delete($this->_src.'/renderer/item');


			/* warn about update requirements only once */
			if(!JFile::exists($this->_src.'/warned.txt') && !$this->checkRequirements()) {

				// set the proceede link with it's behaviour
				$path = JPATH_ROOT . '/tmp/' . basename($this->_src.'_copy');
				$path = str_replace('\\', '\\/', $path);
				$javascript = "document.getElementById('install_directory').value = '{$path}';Joomla.submitbutton3();return false;";
				$this->_error = JText::sprintf('MOD_ZOOITEMPRO_SYS_OUTDATED_EXTENSIONS', $javascript);	

				// rise error
				Jerror::raiseWarning(null, $this->_error);			

				// create a dummy indicational mark file
				$some = 'dummy content';
				JFile::write($this->_src.'/warned.txt', $some);

				// copy the entire install to avoid it delition on cancel
				JFolder::copy($this->_src, JPath::clean(JPATH_ROOT . '/tmp/' . basename($this->_src.'_copy')));

				// cancel update
				return false;
			} else {
				if (JFile::exists($this->_src.'/warned.txt')) JFile::delete($this->_src.'/warned.txt');
			}
		}
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
		// init vars
		$db = JFactory::getDBO();
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
		$type = strtolower($type);
		$release = $parent->get( "manifest" )->version;

		if($type == 'install'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_INSTALL', $this->_ext_name, $release);
		}

		if($type == 'update'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_UPDATE', $this->_ext_name, $release);
		}

		// remove obsolete
		$this->removeObsolete();
	}

	/**
	 * Removes obsolete files and folders
	 * @version 1.1
	 */
	private function removeObsolete()
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
	 * check extensions requirements
	 *
	 * @return  boolean  True on success
	 */
	protected function checkRequirements()
	{
		// get current module manifest file
		$manifest = simplexml_load_file($this->_target . '/mod_zooitempro.xml');
		$min_release = '3.1 BETA';

		// return false if min version not reached
		if( version_compare((string)$manifest->version, (string)$min_release, '<') ) {
			return false;
		}

		return true;
	}

	/**
	 * Check dependencies
	 * @version 1.0
	 *
	 * @return  boolean  True on success
	 */
	protected function checkDependencies($parent)
	{
		// init vars
		$dependencies = $parent->get( "manifest" )->dependencies->attributes();

		// check Joomla
		if ($min_v = (string)$dependencies->joomla) 
		{
			// if up to date
			$joomla_release = new JVersion();
			$joomla_release = $joomla_release->getShortVersion();
			if( version_compare( (string)$joomla_release, $min_v, '<' ) ) {
				$this->_error = JText::sprintf($this->langString('_DEPENDENCY_OUTDATED'), $this->_ext_name, 'http://www.joomla.org', 'Joomla!', $min_v);
				return false;
			}
		}

		// check ZOO
		if ($min_v = (string)$dependencies->zoo) 
		{
			// if installed and enabled
			if (!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')
				|| !JComponentHelper::getComponent('com_zoo', true)->enabled) {
				$this->_error = JText::sprintf($this->langString('_DEPENDENCY_MISSING'), $this->_ext_name, 'http://www.yootheme.com/zoo', 'ZOO');
				return false;
			}

			// if up to date
			$zoo_manifest = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/com_zoo/zoo.xml');

			if( version_compare((string)$zoo_manifest->version, $min_v, '<') ) {
				$this->_error = JText::sprintf($this->langString('_DEPENDENCY_OUTDATED'), $this->_ext_name, 'http://www.yootheme.com/zoo', 'ZOO', $min_v);
				return false;
			}
		}

		// check ZLFW
		if ($min_v = (string)$dependencies->zlfw) 
		{
			// if installed and enabled
			if (!JPluginHelper::getPlugin('system', 'zlframework')) {
				$this->_error = JText::sprintf($this->langString('_DEPENDENCY_MISSING'), $this->_ext_name, 'https://www.zoolanders.com/extensions/zl-framework', 'ZL Framework');
				return false;
			}

			// if up to date
			$zlfw_manifest = simplexml_load_file(JPATH_ROOT.'/plugins/system/zlframework/zlframework.xml');

			if( version_compare((string)$zlfw_manifest->version, $min_v, '<') ) {
				$this->_error = JText::sprintf($this->langString('_DEPENDENCY_OUTDATED'), $this->_ext_name, 'https://www.zoolanders.com/extensions/zl-framework', 'ZL Framework', $min_v);
				return false;
			}
		}

		return true;
	}
}