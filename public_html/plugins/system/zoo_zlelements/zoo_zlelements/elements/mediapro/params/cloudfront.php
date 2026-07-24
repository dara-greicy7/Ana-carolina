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

// JSON
return 
'{
	"_cloudfront":{
		"type":"checkbox",
		"label":"PLG_ZLFRAMEWORK_FLS_CF_INTEGRATION",
		"specific":{
			"label":"PLG_ZLFRAMEWORK_ENABLE"
		},
		"dependents":"cloudfront_fields_wrapper > 1",
		"layout":"separator"
	},
	"cloudfront_fields_wrapper":{
		"type": "wrapper",
		"fields": {

			"_cloudfront_distribution":{
				"type":"text",
				"label":"PLG_ZLFRAMEWORK_FLS_CF_DISTRIBUTION",
				"help":"PLG_ZLFRAMEWORK_FLS_CF_DISTRIBUTION_DESC"
			},
			"_key_pair_id":{
				"type":"text",
				"label":"PLG_ZLFRAMEWORK_FLS_AWS_KEY_PAIR_ID",
				"help":"PLG_ZLFRAMEWORK_FLS_AWS_KEY_PAIR_ID_DESC"
			},
			"_private_key":{
				"type":"text",
				"label":"PLG_ZLFRAMEWORK_FLS_AWS_PRIVATE_KEY",
				"help":"PLG_ZLFRAMEWORK_FLS_AWS_PRIVATE_KEY_DESC"
			}

		}
	}	
}';