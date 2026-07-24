<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

function ZoolandersBuildRoute(&$query) {

	$app = App::getInstance('zoo');

	// init vars
	$segments = array();

	// iterate over routers and execute its routing
	foreach ($app->zl->route->getRouters() as $router) {
		$router->buildRoute($query, $segments);
	}

	return $segments;
}

function ZoolandersParseRoute($segments) {

	$app = App::getInstance('zoo');

	// init vars
	$vars  = array();
	$count = count($segments);

	// fix segments (see JRouter::_decodeSegments)
	foreach (array_keys($segments) as $key) {
		$segments[$key] = str_replace(':', '-', $segments[$key]);
	}

	// iterate over routers and execute its routing
	foreach ($app->zl->route->getRouters() as $router) {
		$router->parseRoute($segments, $vars);
	}

	return $vars;
}