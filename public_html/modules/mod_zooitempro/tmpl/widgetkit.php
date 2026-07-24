<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// get items
$items = mod_zooitemproHelper::getItems($params);

// check items
if (empty($items)) {
	echo JText::_('MOD_ZOOITEMPRO_NO_ITEMS_FOUND');
	return;
}

// check widgetkit
if (!$zoo->zlfw->checkExt('com_widgetkit')) {
	echo JText::_('PLG_ZLFRAMEWORK_WK_NOT_PRESENT');
	return;
}

// load widgetkit
require_once(JPATH_ADMINISTRATOR . '/components/com_widgetkit/widgetkit.php');
$widgetkit = Widgetkit::getInstance();

// register classes
$zoo->loader->register('WidgetWidgetkitHelper', 'root:administrator/components/com_widgetkit/helpers/widget.php');
$zoo->loader->register('WidgetkitWidget', 'root:administrator/components/com_widgetkit/helpers/widget.php');

// init vars
$type = $params->find('layout.widgetkit._widget');
$style = $params->find('layout.widgetkit.settings.style', 'default');
$renderer_layout = basename($params->find('layout.widgetkit.renderer_layout', 'article'), '.php');

// prepare widget settings
$settings = array();
foreach ((array) $params->find('layout.widgetkit.settings') as $key => $value) {
	$settings[$key] = is_numeric($value) ? (float) $value : $value;
}

// init WK Helper
$wk_helper = new WidgetWidgetkitHelper($widgetkit);

// init Widget object
$widget = new WidgetkitWidget($params->get('module_id', uniqid()), $type, $style, 'widgetname', array('settings' => $settings), 0, 0);

// set renderer
$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), $wk_helper['path']->path('zoowidgets:'.$type)));

$i = 0;
$widget_items = array();
foreach ($items as $item) {

	// add the item itself
	$widget_items[$i]['zooitem'] = $item;

	// add item title
	$widget_items[$i]['title'] = $item->name;

	// add item content
	$widget_items[$i]['content'] = $renderer->render('item.'.$renderer_layout, compact('item', 'params'));

	// add item special positions
	$style = isset($widget->settings['style']) ? $widget->settings['style'] : 'default';
	$style_xml = simplexml_load_file($wk_helper['path']->path("{$type}:styles/{$style}/config.xml"));
	foreach ($style_xml->xpath('fields/field') as $field) {
		$name = (string) $field->attributes()->name;
		if (!in_array($name, array('title', 'content'))) {
			$widget_items[$i][$name] = $renderer->checkPosition($name) ? $renderer->renderPosition($name) : '';
		}
	}

	$i++;
}
$widget->items = $widget_items;

// on render event
$wk_helper['event']->trigger('render', array($widget));

$output = $wk_helper[$widget->type]->render($widget);

$wk_helper['event']->trigger('widgetoutput', array(&$output));

echo $output;
