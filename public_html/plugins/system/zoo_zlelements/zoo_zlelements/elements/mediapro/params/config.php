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
		"_player":{
			"type":"layout",
			"label":"PLG_ZLELEMENTS_MP_PLAYER",
			"help":"PLG_ZLELEMENTS_MP_PLAYER_DESC",
			"specific": {
				"path":"elements:mediapro\/assets\/players",
				"mode":"folders"
			},
			"childs":{
				"loadfields": {
					"subfield": {
						"type":"subfield",
						"path":"elements:mediapro\/params\/{value}\/config.php"
					}
				}
			}
		}
	}';