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

		"layout_separator":{
			"type":"separator",
			"text":"No Cache Layout",
			"big":1
		},

		"_link_to_item":{
			"type": "checkbox",
			"label": "PLG_ZLELEMENTS_IMGP_LINK_TO_ITEM",
			"help": "PLG_ZLELEMENTS_IMGP_LINK_TO_ITEM_DESC",
			"default": "0",
			"specific":{
				"label":"JYES"
			}
		}

	}}';