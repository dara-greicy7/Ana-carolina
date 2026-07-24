<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

if (!($wk = (@include JPATH_ADMINISTRATOR.'/components/com_widgetkit/widgetkit-app.php')
    and isset($wk['plugins']['content/zoopro']))
) {
	echo JText::_('PLG_ZLFRAMEWORK_WK2_ZLPLUGIN_MISSING');
    return;
}

$items = mod_zooitemproHelper::getItems($params);

if (empty($items)) {
	echo JText::_('MOD_ZOOITEMPRO_NO_ITEMS_FOUND');
	return;
}

$data = $zoo->data->create(array(
	'mapping_layout' => basename($params->find('layout._mapping', 'mapping'), '.php')
));

$items = $wk['plugins']['content/zoopro']->renderZooItems($items, $data);
include (JPATH_ROOT . '/plugins/system/zlframework/zlframework/elements/pro/tmpl/render/widgetkit2.php');
