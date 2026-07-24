<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

// check ACL
if (!JFactory::getUser()->authorise('core.manage', 'com_zoolanders')) {
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// make sure ZOO is installed and enabled
if (!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')
		|| !JComponentHelper::getComponent('com_zoo', true)->enabled) {

	echo 'Make sure ZOO is installed and enabled';
	return;
}

// make sure ZLFW is installed and enabled
if (!JFile::exists(JPATH_ROOT.'/plugins/system/zlframework/zlframework.xml')
		|| !JPluginHelper::getPlugin('system', 'zlframework')) {

	echo 'Make sure ZL Framework is installed and enabled';
	return;
}

// load config
require_once(JPATH_ROOT.'/plugins/system/zlframework/config.php');

// init vars
$zoo = App::getInstance('zoo');
$path = JPATH_ADMINISTRATOR.'/components/com_zoolanders';
$cache_path = JPATH_ROOT.'/cache/com_zoolanders';
$controller = $zoo->request->getWord('controller');
$task		= $zoo->request->getWord('task');

// register paths
$zoo->path->register($path, 'zl');
$zoo->path->register($path.'/controllers', 'controllers');
$zoo->path->register($path.'/partials', 'partials');
$zoo->path->register($path.'/helpers', 'helpers');

if (!$zoo->path->path('tmp:')) {
	$zoo->path->register(JPATH_ROOT.'/tmp', 'tmp');
}

// build menu
$menu = $zoo->zl->menu->get('nav');

if ($zoo->request->getWord('controller') && $zoo->request->getWord('controller') !== 'extensions') {
	$zoo->zlfw->zlux->loadMainAssets(true);
	$zoo->document->addStylesheet('zlmedia:css/admin.css');
	$zoo->document->addScript('zlmedia:js/admin.js');
	$zoo->document->addScript('zlmedia:vendor/uikit/js/components/notify.min.js');
	$zoo->document->addScript('zlmedia:vendor/uikit/js/components/tooltip.min.js');
	$zoo->document->addScript('zlmedia:vendor/zlux/dist/js/components/notify.min.js');
}

// trigger event for adding config tab menu items
$zoo->event->dispatcher->notify($zoo->event->create(null, 'zoolanders:menuitems', array('tab' => &$menu)));

try {

	// delegate dispatch
	if (!$zoo->zlfw->request->isAjax() && $zoo->request->getWord('format') !== 'raw') {
		echo '<div class="zx">';
			$zoo->dispatch('zoolanders');
		echo '</div>';
	} else {
		$zoo->dispatch('zoolanders');
	}

} catch (AppException $e) {
	$zoo->error->raiseError(500, $e);
}
