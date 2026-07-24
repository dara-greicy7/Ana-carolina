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
		"_width":{
			"type": "text",
			"label": "PLG_ZLELEMENTS_IMGP_RESIZE_IMAGE_WIDTH",
			"help": "PLG_ZLELEMENTS_IMGP_RESIZE_IMAGE_WIDTH_DESC"
		},
		"_height":{
			"type": "text",
			"label": "PLG_ZLELEMENTS_IMGP_RESIZE_IMAGE_HEIGHT",
			"help": "PLG_ZLELEMENTS_IMGP_RESIZE_IMAGE_HEIGHT_DESC"
		},
		"_avoid_cropping":{
			"type": "select",
			"label": "PLG_ZLELEMENTS_IMGP_AVOID_CROPPING",
			"help": "PLG_ZLELEMENTS_IMGP_AVOID_CROPPING_DESC",
			"specific": {
				"options": {
					"JNO":"",
					"JYES":"1",
					"PLG_ZLELEMENTS_IMGP_AC_ONLY_IF_LANDSCAPE":"2",
					"PLG_ZLELEMENTS_IMGP_AC_ONLY_IF_PORTRAIT":"3"
				}
			}
		}, 
		"_avoid_resize_small":{
			"type": "radio",
			"label": "PLG_ZLELEMENTS_IMGP_AVOID_SMALL_TO_LARGE",
			"help": "PLG_ZLELEMENTS_IMGP_AVOID_SMALL_TO_LARGE_DESC",
			"default": "0"
		}
		'.(isset($params['link']) ? ', "_link_to_item":{
			"type": "checkbox",
			"label": "PLG_ZLELEMENTS_IMGP_LINK_TO_ITEM",
			"help": "PLG_ZLELEMENTS_IMGP_LINK_TO_ITEM_DESC",
			"default": "0",
			"specific":{
				"label":"JYES"
			},
			"dependents":"_lightbox !> 1 "
		}' : '').'
	}';