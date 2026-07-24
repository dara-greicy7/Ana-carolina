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
	"_width":{
		"type":"text",
		"label":"PLG_ZLFRAMEWORK_WIDTH",
		"default":"480"
	},
	"_height":{
		"type":"text",
		"label":"PLG_ZLFRAMEWORK_HEIGHT",
		"default":"270"
	},
	"_autoplay":{
		"type": "checkbox",
		"label": "PLG_ZLELEMENTS_MP_AUTOPLAY",
		"help": "PLG_ZLELEMENTS_MP_AUTOPLAY_DESC",
		"specific":{
			"label":"JYES"
		}
	},
	"_skin":{
		"type":"layout",
		"label":"PLG_ZLELEMENTS_MP_SKIN",
		"help":"PLG_ZLELEMENTS_MP_SKIN_DESC",
		"specific": {
			"path":"elements:'.$el_type.'\/assets\/players\/'.$player.'\/skins",
			"mode":"folders"
		}
	}

}';