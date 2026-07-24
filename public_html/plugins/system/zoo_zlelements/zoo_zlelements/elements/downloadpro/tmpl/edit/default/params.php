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
$el_type = $element->getElementType();

// JSON
return 
'{"fields": {

	"_sublayout":{
		"type": "layout",
		"label": "PLG_ZLFRAMEWORK_SUB_LAYOUT",
		"help": "PLG_ZLFRAMEWORK_SUB_LAYOUT_DESC",
		"default": "_default.php",
		"specific": {
			"path":"elements:'.$el_type.'\/tmpl\/edit\/default\/_sublayouts",
			"regex":'.json_encode('^([_A-Za-z0-9]*)\.php$').',
			"minimum_options":"2"
		},
		"childs":{						
			"loadfields": {
				"layout_wrapper":{
					"type": "fieldset",
					"min_count":"1",
					"fields": {

						"subfield": {
							"type":"subfield",
							"path":"elements:'.$el_type.'\/tmpl\/edit\/default\/_sublayouts\/{value}\/params.php"
						}

					}
				}
			}
		}
	}

}}';