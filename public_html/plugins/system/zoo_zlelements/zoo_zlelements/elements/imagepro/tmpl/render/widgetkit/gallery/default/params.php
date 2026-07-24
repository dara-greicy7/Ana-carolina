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
			"path":"elements:pro\/tmpl\/render\/widgetkit\/gallery\/default\/settings.php"
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
			"dependents":"_link_to_item_notice > 1"
		},
		"_link_to_item_notice":{
			"type":"info",
			"specific":{
				"text":"Link feature is not compatible with Lightbox Integration."
			}
		},

		"main_wrapper_lightbox":{
			"type": "wrapper",
			"fields": {

				'./* Lightbox Integration */'
				"lightbox":{
					"type": "checkbox",
					"label": "PLG_ZLFRAMEWORK_LIGHTBOX_INTEGRATION",
					"default": "0",
					"specific":{
						"label":"PLG_ZLFRAMEWORK_ENABLE"
					},
					"dependents":"wrapper_lightbox > 1",
					"layout":"separator"
				},
				"wrapper_lightbox":{
					"type": "wrapper",
					"fields": {
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
						"lightbox_settings": {
							"type":"subfield",
							"path":"elements:pro\/tmpl\/render\/widgetkit\/lightbox\/lightbox_defaults.php"
						}
					}
				}

			}
		}
		
	},
	"control": "settings"}';