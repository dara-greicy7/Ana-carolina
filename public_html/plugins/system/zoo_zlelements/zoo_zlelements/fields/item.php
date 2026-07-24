<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

class JElementItem extends JElement {

	var	$_name = 'Item';

	function fetchElement($name, $value, $node, $control_name, $subloaded = false, $config = array(), $params = array()) {


		$app = App::getInstance('zoo');

		$app->html->_('behavior.modal', 'a.modal');
		$app->document->addScript('zlpath:fields/item.js');

		// set values
		if ($subloaded) {
			// if forms part of another JElement use the passed ones
			$params  = $app->data->create($params);
			$config  = $app->data->create($config);
			$value   = $app->data->create($params->find($name.'.item', array()));
			$control = "{$control_name}[{$name}][item]";
		} else { 
			// if standalone, get params from parent
			$params  = $app->parameterform->convertParams($this->_parent);
			$config  = $element->config;
			$value   = $app->data->create($params->get($name, array()));
			$control = "{$control_name}[{$name}]";
		}
		
		$html = array();
		$html[] = '<div class="zl-fields item-options">';
		
			// create application
			$options = array('- '.JText::_('Select').' -' => '');
			foreach ($app->table->application->all(array('order' => 'name')) as $application) {
				// application option
				$options[$application->name] = $application->id;
			}

			// select application
			$html[] = $app->zlfwhtml->_('zoo.select', $control.'[_app]', $options, $value->get('_app', ''), JText::_('Application'), 'application');
			
			// create items html
			$item_name  = JText::_('Select Item');
			if ($item_id = $value->get('_item_id')) {
				$item = $app->table->item->get($item_id);
				$item_name = $item->name;
			}
			
			$link = $app->link(array('controller' => 'item', 'task' => 'element', 'tmpl' => 'component', 'func' => 'selectZooItem', 'object' => $name), false);

			$html[] = '<div class="row item">';
			$html[] = '<input type="text" id="'.$name.'_name" value="'.htmlspecialchars($item_name, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
			$html[] = '<a class="modal" title="'.JText::_('Select Item').'"  href="#" rel="{handler: \'iframe\', size: {x: 850, y: 500}}">'.JText::_('Select').'</a>';
			$html[] = '<input type="hidden" id="'.$name.'_id" name="'.$control.'[_item_id]'.'" value="'.(int)$item_id.'" />';
			$html[] = '</div>';

		$html[] = '</div>';

		$javascript  = 'jQuery(function($) { jQuery(".item-options").ZooItem({ url: "'.$link.'", msgSelectItem: "'.JText::_('Select Item').'" }); });';
		$javascript  = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";

		return implode("\n", $html).$javascript;
	}

}