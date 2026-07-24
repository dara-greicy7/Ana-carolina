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
$player  = $element->config->find('specific._player');
$el_type = $element->getElementType();

// JSON
return
'{
	"_autoplay":{
		"type":"checkbox",
		"label":"PLG_ZLELEMENTS_MP_AUTOPLAY",
		"help":"PLG_ZLELEMENTS_MP_AUTOPLAY_DESC",
		"specific":{
			"label":"JYES"
		}
	},
	"_loop":{
		"type":"checkbox",
		"label":"PLG_ZLELEMENTS_MP_LOOP",
		"help":"PLG_ZLELEMENTS_MP_LOOP_DESC",
		"specific":{
			"label":"JYES"
		}
	},
	"_width":{
		"type":"text",
		"label":"PLG_ZLFRAMEWORK_WIDTH",
		"help":"PLG_ZLELEMENTS_MP_JP_VIDEO_SIZE_DESC",
		"default":"480"
	},

	"screen_separator":{
		"type":"separator",
		"text":"PLG_ZLELEMENTS_MP_JP_SCREEN"
	},
	"_screen_display":{
		"type":"radio",
		"label":"PLG_ZLELEMENTS_MP_DISPLAY",
		"help":"PLG_ZLELEMENTS_MP_JP_SCREEN_DISPLAY_DESC",
		"default":"1",
		"dependents":"screen_wrapper > 1",
		"specific":{
			"label":"JYES"
		}
	},
	"screen_wrapper":{
		"type":"wrapper",
		"fields": {
			"_height":{
				"type":"text",
				"label":"PLG_ZLFRAMEWORK_HEIGHT",
				"help":"PLG_ZLELEMENTS_MP_JP_VIDEO_SIZE_DESC",
				"default":"270"
			}
		}
	}		

}';