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

	"renderer_layout":{
		"type":"layout",
		"label":"MOD_ZOOITEMPRO_FIELD_RENDERER_LAYOUT_LABEL",
		"help":"MOD_ZOOITEMPRO_FIELD_RENDERER_LAYOUT_DESC",
		"specific": {
			"path":"modules:mod_zooitempro\/renderer\/item",
			"regex":' . json_encode('^([^_][_A-Za-z0-9]*)\.php$') . '
		},
		"check_old_value":{
			"id":"renderer_layout",
			"adjust_ctrl":{
				"pattern":'.json_encode('/\[layout\]/').',
				"replacement":""
			}
		}
	},
	"media_position":{
		"type":"select",
		"label":"Media Position",
		"help":"MEDIAPOSITION_DESCRIPTION",
		"specific": {
			"options":{
				"Left":"left",
				"Right":"right",
				"Top":"top",
				"Middle":"middle",
				"Bottom":"bottom"
			}
		},
		"adjust_ctrl":{
			"pattern":'.json_encode('/\[layout\]/').',
			"replacement":""
		}
	},
	"cache_time": {
		"type":"text",
		"label":"MOD_ZOOITEMPRO_FIELD_CACHE_TIME_LABEL",
		"help":"MOD_ZOOITEMPRO_FIELD_CACHE_TIME_DESC",
		"default":"3600"
	},
	"_separator1":{
		"type":"separator",
		"specific":{
			"title":"PLG_ZLFRAMEWORK_ITEMS"
		},
		"layout":"section"
	},
	"itemsfilter":{
		"type":"subfield",
		"path":"modules:mod_zooitempro\/fields\/zlitemsfilter.php"
	}

}}';