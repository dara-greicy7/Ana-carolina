<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// load zoo config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

// init vars
$zoo = App::getInstance('zoo');
$path = dirname(__FILE__);
$controller = $zoo->request->getWord('controller');

// register paths
$zoo->path->register($path.'/assets', 'assets');
$zoo->path->register($path.'/controllers', 'controllers');

// try to get the controller from the menu
if (!$controller) {
	$menu_item = $zoo->menu->getActive();

	if (@$menu_item->query['view'] == 'extension') {
		$controller = @$menu_item->params->get('extension')->view;
	}
	
	$zoo->request->setVar('controller', $controller);
}

try {

	// delegate dispatch
	$zoo->dispatch();

} catch (AppException $e) {
	$zoo->error->raiseError(500, $e);
}