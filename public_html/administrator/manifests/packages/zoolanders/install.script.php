<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

class pkg_zoolandersInstallerScript {

	protected $_error;

	public function install($parent) {}

	public function uninstall($parent) {}

	public function update($parent) {}

	public function preflight($type, $parent)
	{
		// check dependencies if not uninstalling
		if($type != 'uninstall' && !$this->checkRequirements($parent)){
			Jerror::raiseWarning(null, $this->_error);
			return false;
		}

		// check ZOOtools is uninstalled
		if ($type != 'uninstall' && $this->getPlugin('zootools')) {
			JError::raiseWarning(null, JText::_('ZOOtools has been deprecated and merged into ZOOlanders component, please uninstall it before relaunching the installation.'));
			return false;
		}
	}

	public function postflight($type, $parent, $results)
	{
		$extensions = array();
		foreach($results as $result) {
			$extensions[] = (object) array('name' => $result['name'], 'status' => $result['result'], 'message' => $result['result'] ? ($type == 'update' ? 'Updated' : 'Installed').' successfully' : 'NOT Installed');
		}

		// display extension installation results
		self::displayResults($extensions, 'Extensions', 'Extension');
	}

	/**
	 * Retrieve an plugin object
	 *
	 * @param  string $name The plugin name
	 * @param  string $type The plugin type
	 *
	 * @return Object The requested plugin
	 */
	protected function getPlugin($name, $type = 'system')
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__extensions WHERE element LIKE ' . $db->Quote($name) . ' AND folder LIKE ' . $db->Quote($type) . ' LIMIT 1';

		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 * check general requirements
	 * @version 1.1
	 *
	 * @return  boolean  True on success
	 */
	protected function checkRequirements($parent)
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
				$this->_error = "Joomla! v{$min_v} or higher required, please update it and retry the installation.";
				return false;
			}
		}

		// check ZOO
		if ($min_v = (string)$dependencies->zoo) 
		{
			// if installed and enabled
			if (!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')
				|| !JComponentHelper::getComponent('com_zoo', true)->enabled) {
				$this->_error = "ZOOlanders Extensions relies on <a href=\"http://www.yootheme.com/zoo\" target=\"_blank\">ZOO</a>, be sure is installed and enabled before retrying the installation.";
				return false;
			}

			// if up to date
			$zoo_manifest = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/com_zoo/zoo.xml');

			if( version_compare((string)$zoo_manifest->version, $min_v, '<') ) {
				$this->_error = "ZOO v{$min_v} or higher required, please update it and retry the installation.";
				return false;
			}
		}

		// check Uikit
		if (($min_v = (string)$dependencies->uikit) && JPluginHelper::getPlugin('system', 'zoocart')) {
			$db = JFactory::getDBO();
			$db->setQuery("SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1");
			$defaultTemplate = $db->loadResult();
			$uikit_file      = JPATH_SITE."/templates/$defaultTemplate/warp/vendor/uikit/js/uikit.js";
			$warp_xml_file   = JPATH_SITE."/templates/$defaultTemplate/warp/warp.xml";

			if (JFile::exists($uikit_file) && JFile::exists($warp_xml_file)) {

				$uikit_file_content = file_get_contents($uikit_file);
				preg_match('/\.version="(.+?)"/', $uikit_file_content, $uikit_version);

				if (version_compare((string)$uikit_version[1], $min_v, '<')) {
					$this->_error = "Warp theme with an outdated UIkit version has been detected. Being the minimum UIkit v{$min_v} required, please update your Warp Theme and try the installation again.";
					return false;
				}
			}
		}

		return true;
	}

	protected function displayResults($result, $name, $type) {

?>

	<h3><?php echo JText::_($name); ?></h3>
	<table class="adminlist table table-bordered table-striped" width="100%">
		<thead>
			<tr>
				<th class="title"><?php echo JText::_($type); ?></th>
				<th width="60%"><?php echo JText::_('Status'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				foreach ($result as $i => $ext) : ?>
				<tr class="row<?php echo $i++ % 2; ?>">
					<td class="key"><?php echo $ext->name; ?></td>
					<td>
						<?php $style = $ext->status ? 'font-weight: bold; color: green;' : 'font-weight: bold; color: red;'; ?>
						<span style="<?php echo $style; ?>"><?php echo JText::_($ext->message); ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php

	}
}