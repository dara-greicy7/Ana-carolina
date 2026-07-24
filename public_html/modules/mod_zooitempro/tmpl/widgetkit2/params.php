<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

$zoo = App::getInstance('zoo');

// load default and current language, necesary for when ajax loading
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
		"path":"elements:pro\/tmpl\/render\/widgetkit2\/params.php"
	},
	"_mapping":{
		"type": "layout",
		"label": "PLG_ZLFRAMEWORK_MAPPING",
		"help": "PLG_ZLFRAMEWORK_WK2_MAPPING_DESC",
		"specific":{
			"path":"root:plugins\/system\/widgetkit_zl\/plugin\/renderer\/item",
			"regex":'.json_encode('^([_A-Za-z0-9]*)\.php$').'
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