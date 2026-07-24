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
$player = $element->config->find('specific._player');
$el_type = $element->getElementType();

// JSON
return 
'{"fields": {

	"layout_wrapper":{
		"type": "fieldset",
		"min_count":"1",
		"fields": {

			"layout_separator":{
				"type":"separator",
				"text":"PLG_ZLFRAMEWORK_DEFAULT_LAYOUT",
				"big":1
			},

			"player_options_subfield":{
				"type":"subfield",
				"path":"elements:'.$el_type.'\/params\/'.$player.'\/render.php"
			},

			"cloudfront_subfield":{
				"type":"subfield",
				"path":"elements:'.$el_type.'\/params\/cloudfront.php"
			}

		}
	}

}}';