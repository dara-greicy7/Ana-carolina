<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');	
	
	return 
	'{
		"layout_separator":{
			"type":"separator",
			"text":"ImageLink Layout",
			"big":1
		},

		"_download_name":{
			"type": "text",
			"label": "PLG_ZLELEMENTS_DWP_DOWNLOAD_NAME",
			"help": "PLG_ZLELEMENTS_DWP_DOWNLOAD_NAME_DESC",
			"default": "Download {filename}",
			"adjust_ctrl":{
				"pattern":'.json_encode('/layout/').',
				"replacement":"specific"
			}
		},
		"_target":{
			"type":"checkbox",
			"label":"PLG_ZLFRAMEWORK_NEW_WINDOW",
			"help":"PLG_ZLELEMENTS_DWP_NEW_WINDOW_DESC",
			"specific": {
				"label":"JYES"
			}
		},
		"_set":{
			"type": "layout",
			"label": "PLG_ZLELEMENTS_DWP_SET",
			"default": "default",
			"specific": {
				"path":"elements:downloadpro\/tmpl\/render\/default\/_sublayouts\/_imagelink\/sets",
				"mode":"folders"
			},
			"control": "sublayout"
		}

	}';