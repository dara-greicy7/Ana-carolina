<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

$zoo = App::getInstance('zoo');

$zoo->system->language->load('mod_zooitempro', JPATH_SITE, 'en-GB', true);
$zoo->system->language->load('mod_zooitempro', JPATH_SITE, null, true);

return 
'{"fields": {

	"_separator":{
		"type":"separator",
		"specific":{
			"title":"Widgetkit"
		},
		"layout":"section"
	},
	"widgetkit":{
		"type":"subfield",
		"path":"modules:mod_zooitempro\/fields\/widgetkit.json.php",
		"arguments":{
			"params":{
				"widgets":{
					"Accordion":"accordion",
					"Slideset":"slideset",
					"Slideshow":"slideshow"
				}
			}
		}
	},
	"_separator1":{
		"type":"separator",
		"specific":{
			"title":"PLG_ZLFRAMEWORK_ITEMS"
		},
		"layout":"section"
	},
	"cache_time": {
		"type":"text",
		"label":"MOD_ZOOITEMPRO_FIELD_CACHE_TIME_LABEL",
		"help":"MOD_ZOOITEMPRO_FIELD_CACHE_TIME_DESC",
		"default":"3600"
	},
	"itemsfilter":{
		"type":"subfield",
		"path":"modules:mod_zooitempro\/fields\/zlitemsfilter.php"
	}

}}';