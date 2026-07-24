<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$zoo = App::getInstance('zoo');
$player = $element->config->find('specific._player');

// set custom params
$params->set('layout', array(
	'path' => "elements:mediapro/tmpl/render/$player",
	'help' => $params->find('load.help'),
	'label' => $params->find('load.label')
));

// import the ZL Field JSON Layout
return include($zoo->path->path('zlfield:json/layout.json.php'));