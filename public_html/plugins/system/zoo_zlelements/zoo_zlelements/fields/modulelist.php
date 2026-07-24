<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

class JElementModuleList extends JElement {

	var	$_name = 'ModuleList';

	function fetchElement($name, $value, &$node, $control_name) {
	
		// init var
		$app = App::getInstance('zoo');
	
		$options = array($app->html->_('select.option', '', '- '.JText::_('Select Module').' -'));
		return $app->html->_('zoo.modulelist', $options, $control_name.'[module]', null, 'value', 'text', $value);

	}
}