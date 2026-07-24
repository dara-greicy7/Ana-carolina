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

		"main_layout_wrapper":{
			"type": "fieldset",
			"fields": {

				"image_separator":{
					"type": "separator",
					"text": "PLG_ZLFRAMEWORK_LAYOUT",
					"big":"1"
				},
				"image_options": {
					"type": "subfield",
					"path":"elements:'.$element->getElementType().'\/params\/image.php",
					"adjust_ctrl":{
						"pattern":'.json_encode('/\[layout\]/').',
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
						"pattern":'.json_encode('/\[layout\]/').',
						"replacement":"[specific]"
					}
				},

				"qtip_layout_wrapper":{
					"type": "control_wrapper",
					"fields": {
						
						"layout_sep":{
							"type": "separator",
							"text": "PLG_ZLFRAMEWORK_QTIP_DISPLAY",
							"big": "1"
						},
						"qtip_options":{
							"type": "wrapper",
							"fields": {
								"_subfield": {
									"type": "subfield",
									"path":"zlfw:elements\/pro\/tmpl\/render\/qtip\/qtip_options.php"
								}
								
							}
						},
						
						"qtip_layout":{
							"type": "wrapper",
							"fields": {
								"sep-specific":{
									"type": "separator",
									"text": "PLG_ZLFRAMEWORK_QTIP_LAYOUT",
									"big":"true"
								},
								"image_options": {
									"type": "subfield",
									"path":"elements:'.$element->getElementType().'\/params\/image.php",
									"arguments":{
										"params":{
											"link":"true"
										}
									},
									"control":"specific"
								},
								
								"sep_filter":{
									"type": "separator",
									"text": "PLG_ZLFRAMEWORK_FILTER"
								},
								"filter_options": {
									"type": "subfield",
									"path":"zlfield:json\/filter.json.php",
									"control":"filter"
								},
								
								"sep_separator":{
									"type": "separator",
									"text": "PLG_ZLFRAMEWORK_SP_SEPARATOR"
								},
								"separator_options": {
									"type": "subfield",
									"path":"zlfield:json\/separator.json.php",
									"control":"separator"
								}
							}
						}

					},
					"control": "qtip"
				}
			}
		}
	}}';
		