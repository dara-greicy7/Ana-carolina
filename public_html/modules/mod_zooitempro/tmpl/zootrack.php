<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// get ZOOtrack selected layout
$zt_layout = $params->find('layout._zootrack_layout', 'current.php');

// set renderer
$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), $zoo->path->path('modules:mod_zooitempro')));
$renderer_layout = basename($params->find('layout.renderer_layout', 'default'), '.php');

// render layout
if ($layout = $zoo->path->path('modules:mod_zooitempro/tmpl/zootrack/layouts/'.$zt_layout)) {
	echo $zoo->zlfw->renderLayout($layout, compact('params', 'renderer', 'renderer_layout', 'zoo'));
}