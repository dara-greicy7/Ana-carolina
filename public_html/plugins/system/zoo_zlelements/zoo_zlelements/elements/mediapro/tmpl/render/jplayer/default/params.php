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
$plugin = 'jplayer';
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
				"text":"PLG_ZLELEMENTS_MP_PLAYLIST_LAYOUT",
				"big":1
			},

			"layout_wrapper":{
				"type":"control_wrapper",
				"fields": {

					"player_options_subfield":{
						"type":"subfield",
						"path":"elements:'.$el_type.'\/params\/'.$plugin.'\/render.php"
					},
					"_skin":{
						"type":"select",
						"label":"PLG_ZLELEMENTS_MP_SKIN",
						"help":"PLG_ZLELEMENTS_MP_SKIN_DESC",
						"default":"pink.flag",
						"specific":{
							"options": {
								"Pink Flag":"pink.flag",
								"Blue Monday":"blue.monday"
							}
						}
					},
					"preview_img_subfield":{
						"type":"subfield",
						"path":"elements:'.$el_type.'\/params\/preview_img.php"
					},
					"_playlist":{
						"type": "checkbox",
						"label": "PLG_ZLELEMENTS_MP_PLAYLIST_MODE",
						"specific":{
							"label":"PLG_ZLFRAMEWORK_ENABLE"
						},
						"layout":"separator"
					}
				}
			}

		}
	}

}}';