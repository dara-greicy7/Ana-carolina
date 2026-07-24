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

	// init vars
	$group = $this->app->request->getString('group');
	$type = $this->app->request->getString('type');

	return 
	'{
		"_preview_img":{
			"type":"select",
			"label":"PLG_ZLELEMENTS_MP_PREVIEW_IMG",
			"help":"PLG_ZLELEMENTS_MP_PREVIEW_IMG_DESC",
			"specific":{
				"options": {
					"PLG_ZLFRAMEWORK_NONE":"0",
					"PLG_ZLELEMENTS_MP_FROM_ELEMENT":"1",
					"PLG_ZLELEMENTS_MP_FROM_FILE":"2"
				}
			},
			"dependents":"_preview_img_element > 1 | _preview_img_file > 2"
		},
		"_preview_img_element":{
			"type":"elements",
			"label":" ",
			"help":"PLG_ZLELEMENTS_MP_PREVIEW_IMG_ELEMENT_DESC",
			"specific":{
				"elements":"image imagepro",
				"apps":"'.$group.'",
				"types":"'.$type.'"
			}
		},
		"_preview_img_file":{
			"type":"text",
			"label":" ",
			"help":"PLG_ZLELEMENTS_MP_PREVIEW_IMG_FILE_DESC",
			"specific":{
				"placeholder":"PLG_ZLFRAMEWORK_URL"
			}
		}
	}';