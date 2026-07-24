<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

$zoo = App::getInstance('zoo');

$zoo->system->language->load('mod_zooitempro', JPATH_SITE, 'en-GB', true);
$zoo->system->language->load('mod_zooitempro', JPATH_SITE, null, true);

// init vars
$widgets = $params->get('widgets', array());

return
'{"fields": {
	"_wkcheck_info":{
		"type": "info",
		"specific":{
			"text": "PLG_ZLFRAMEWORK_WK_NOT_PRESENT"
		},
		"label": "PLG_ZLFRAMEWORK_WARNING",
		"renderif":{
			"com_widgetkit":"0"
		}
	},

	"_widget":{
		"type": "select",
		"label": "PLG_ZLFRAMEWORK_WIDGET",
		"specific": {
			"options":'. json_encode($widgets) .'
		},
		"childs": {
			"loadfields":{

				"subfield": {
					"type":"subfield",
					"path":"modules:mod_zooitempro\/fields\/widgetkit.style.json.php"
				}
				
			}
		}
	}	

},
"control": "widgetkit"}';