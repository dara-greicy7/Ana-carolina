<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');
require_once(dirname(__FILE__).'/helper.php');

$zoo    = App::getInstance('zoo');
$path   = dirname(__FILE__);
$params = $zoo->data->create($params->toArray());

$zoo->system->language->load('com_zoo');
$zoo->path->register($path, 'mod_zooitempro');

include(JModuleHelper::getLayoutPath('mod_zooitempro', basename($params->find('layout._layout', 'default'), '.php')));