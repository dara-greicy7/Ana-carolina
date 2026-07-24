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
	'{"fields": {

		"widget_separator":{
			"type": "separator",
			"text": "PLG_ZLFRAMEWORK_LAYOUT",
			"big":"1"
		},
		"widget_settings": {
			"type":"subfield",
			"path":"elements:pro\/tmpl\/render\/widgetkit\/gallery\/showcase\/settings.php"
		},
		"image_separator":{
			"type": "separator",
			"text": "PLG_ZLFRAMEWORK_IMAGE"
		},
		"image_options": {
			"type": "subfield",
			"path":"elements:'.$element->getElementType().'\/params\/image.php",
			"adjust_ctrl":{
				"pattern":'.json_encode('/\[layout\]\[widgetkit\]\[settings\]/').',
				"replacement":"[specific]"
			}
		},
		"_link_to_item":{
			"type": "checkbox",
			"label": "PLG_ZLELEMENTS_IMGP_LINK_TO_ITEM",
			"help": "PLG_ZLELEMENTS_IMGP_LINK_TO_ITEM_DESC",
			"default": "0",
			"specific":{
				"label":"JYES"
			},
			"adjust_ctrl":{
				"pattern":'.json_encode('/\[layout\]\[widgetkit\]\[settings\]/').',
				"replacement":"[specific]"
			},
			"dependents":"main_wrapper_lightbox !> 1"
		}
		
	},
	"control": "settings"}';