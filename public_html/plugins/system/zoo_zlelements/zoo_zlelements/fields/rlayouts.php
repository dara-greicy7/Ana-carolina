<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JElementRlayouts extends JElement {
	
	public	$_name = 'Rlayouts';
	protected	$_renderer;

	function fetchElement($name, $value, &$node, $control_name) {

		// init vars
		$app 		 = App::getInstance('zoo');
		$class       = $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"';
		$constraint  = $node->attributes('constraint');
		$options 	 = array($app->html->_('select.option', '', JText::_('Item Name')));
		
		// select item layout
		$id = "{$control_name}[{$name}]";
		return $app->zlfwhtml->_('zoo.itemLayoutList', $id, $value, $constraint, null, $options, $class);
		
	}
	
}