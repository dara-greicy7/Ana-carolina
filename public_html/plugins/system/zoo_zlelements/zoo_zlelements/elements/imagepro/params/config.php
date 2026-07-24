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

	$edit_path = array();
	$edit_path[] = 'zlpath:elements\/'.$element->getElementType().'\/tmpl\/edit';
	if (is_object($element->getType())) $edit_path[] = 'applications:'.$element->getType()->getApplication()->getGroup().'\/elements\/'.$element->getElementType().'\/tmpl\/edit'; // zoo template overide path

	return
	'{
		"_edit_sublayout":{
			"type": "layout",
			"label": "PLG_ZLELEMENTS_IMGP_EDIT_SUBLAYOUT",
			"default": "_edit.php",
			"specific": {
				"path":"'.implode(',', $edit_path).'",
				"regex":'.json_encode('^([_][_A-Za-z0-9]*)\.php$').',
				"min_opts":"2"
			}
		},
		"_custom_options":{
			"type": "select",
			"label": "PLG_ZLELEMENTS_IMGP_CUSTOM_OPTIONS",
			"help": "PLG_ZLELEMENTS_IMGP_CUSTOM_OPTIONS_DESC",
			"default": "1",
			"specific":{
				"options": {
					"JYES":"1",
					"JNO":"0"
				}
			},
			"dependents":"_custom_title > 1 | _custom_link > 1 | _custom_lightbox > 1 | _custom_overlay > 1"
		},
		"_custom_title":{
			"type": "radio",
			"label": "PLG_ZLFRAMEWORK_TITLE",
			"help": "PLG_ZLELEMENTS_IMGP_CUSTOM_TITLE_DESC",
			"default": "1",
			"specific":{
				"options": {
					"JYES":"1",
					"JNO":"0"
				}
			}
		},
		"_custom_link":{
			"type": "radio",
			"label": "PLG_ZLFRAMEWORK_LINK",
			"help": "PLG_ZLELEMENTS_IMGP_CUSTOM_LINK_DESC",
			"default": "0",
			"specific":{
				"options": {
					"JYES":"1",
					"JNO":"0"
				}
			}
		},
		"_custom_lightbox":{
			"type": "radio",
			"label": "PLG_ZLFRAMEWORK_LIGHTBOX",
			"help": "PLG_ZLELEMENTS_IMGP_CUSTOM_LIGHTBOX_DESC",
			"default": "1",
			"specific":{
				"options": {
					"JYES":"1",
					"JNO":"0"
				}
			}
		},
		"_custom_overlay":{
			"type": "radio",
			"label": "PLG_ZLELEMENTS_IMGP_OVERLAY",
			"help": "PLG_ZLELEMENTS_IMGP_CUSTOM_OVERLAY_DESC",
			"default": "1",
			"specific":{
				"options": {
					"JYES":"1",
					"JNO":"0"
				}
			}
		}
	}';