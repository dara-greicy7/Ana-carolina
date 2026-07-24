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
		"_download_mode":{
			"type": "select",
			"label": "PLG_ZLELEMENTS_DWP_MODE",
			"help": "PLG_ZLELEMENTS_DWP_MODE_DESC",
			"specific":{
				"options":{
					"PLG_ZLELEMENTS_DWP_DIRECT":"0",
					"PLG_ZLELEMENTS_DWP_ATTACHMENT":"1",
					"PLG_ZLELEMENTS_DWP_PROTECTED":"2"
				}
			}
		}
	}';