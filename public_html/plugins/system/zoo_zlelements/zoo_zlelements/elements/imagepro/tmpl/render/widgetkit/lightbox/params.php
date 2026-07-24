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

		"image_separator":{
			"type": "separator",
			"text": "PLG_ZLFRAMEWORK_LAYOUT",
			"big":"1"
		},
		"image_options": {
			"type": "subfield",
			"path":"elements:'.$element->getElementType().'\/params\/image.php",
			"adjust_ctrl":{
				"pattern":'.json_encode('/\[layout\]\[widgetkit\]/').',
				"replacement":"[specific]"
			}
		},

		"wrapper_lightbox_settings":{
			"type": "control_wrapper",
			"fields": {
				"_separatorid":{
					"type": "separator",
					"text": "Lightbox"
				},
				"width":{
					"type": "text",
					"label": "PLG_ZLFRAMEWORK_WIDTH",
					"specific":{
						"placeholder":"auto"
					}
				},
				"height":{
					"type": "text",
					"label": "PLG_ZLFRAMEWORK_HEIGHT",
					"specific":{
						"placeholder":"auto"
					}
				},
				"avoid_cropping":{
					"type": "select",
					"label": "PLG_ZLELEMENTS_IMGP_AVOID_CROPPING",
					"help": "PLG_ZLELEMENTS_IMGP_AVOID_CROPPING_DESC",
					"specific": {
						"options": {
							"JNO":"0",
							"JYES":"1",
							"PLG_ZLELEMENTS_IMGP_AC_ONLY_IF_LANDSCAPE":"2",
							"PLG_ZLELEMENTS_IMGP_AC_ONLY_IF_PORTRAIT":"3"
						}
					}
				},
				"lightbox_caption":{
					"type": "select",
					"label": "PLG_ZLFRAMEWORK_TITLE",
					"default": "1",
					"specific":{
						"options":{
							"PLG_ZLFRAMEWORK_DISABLED":"0",
							"PLG_ZLFRAMEWORK_DEFAULT":"1",
							"PLG_ZLFRAMEWORK_CUSTOM":"2"
						}
					},
					"dependents":"_lightbox_custom_title > 2"
				},
				"_lightbox_custom_title":{
					"label": "PLG_ZLFRAMEWORK_CUSTOM_TITLE",
					"type":"text"
				},
				"lightbox_defaults": {
					"type":"subfield",
					"path":"elements:pro\/tmpl\/render\/widgetkit\/lightbox\/lightbox_defaults.php"
				},

				'./* Spotlight Integration */'
				"spotlight":{
					"type": "checkbox",
					"label": "PLG_ZLFRAMEWORK_SPOTLIGHT_INTEGRATION",
					"default": "0",
					"specific":{
						"label":"PLG_ZLFRAMEWORK_ENABLE"
					},
					"dependents":"wrapper_spotlight > 1",
					"layout":"separator"
				},
				"wrapper_spotlight":{
					"type": "wrapper",
					"fields": {
						"spotlight_settings": {
							"type":"subfield",
							"path":"elements:pro\/tmpl\/render\/widgetkit\/spotlight\/settings.php",
							"arguments":{
								"params":{
									"captions":"true"
								}
							}
						}
					}
				}
				
			},
			"control": "settings"
		}

	}}';