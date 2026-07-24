<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// get and map data
$items = array();
foreach ($this as $self) {

	$resources = $this->getValidResources($this->get('file'));

	$data = array('title' => $this->get('title'), 'link' => $this->get('link'));

	foreach ($resources as $resource) {
		$items[] = array_replace($data, array('media' => $resource));
	}

}

// apply offset/limit
$offset = (int)$params->find('filter._offset');
$limit  = (int)$params->find('filter._limit');
 
$items = array_slice($items, $offset ?: null, $limit ?: null);

// render
include (JPATH_ROOT . '/plugins/system/zlframework/zlframework/elements/pro/tmpl/render/widgetkit2.php');
