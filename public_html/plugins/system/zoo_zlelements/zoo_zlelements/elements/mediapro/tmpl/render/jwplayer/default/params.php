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
				"dependents":"playlist_fields_wrapper > 1",
				"layout":"separator"
			},
			"playlist_fields_wrapper":{
				"type": "wrapper",
				"fields": {
					"_playlist_position":{
						"type":"select",
						"label":"PLG_ZLELEMENTS_MP_JW_PLAYLIST_POSITION",
						"help":"PLG_ZLELEMENTS_MP_JW_PLAYLIST_POSITION_DESC",
						"specific":{
							"options": {
								"PLG_ZLELEMENTS_MP_JW_PLAYLIST_IN_BAR":"none",
								"PLG_ZLELEMENTS_MP_JW_PLAYLIST_BOTTOM":"bottom",
								"PLG_ZLELEMENTS_MP_JW_PLAYLIST_RIGHT":"right"
							}
						},
						"dependents":"_playlist_size !> none"
					},
					"_playlist_size":{
						"type":"text",
						"label":"PLG_ZLELEMENTS_MP_JW_PLAYLIST_SIZE",
						"help":"PLG_ZLELEMENTS_MP_JW_PLAYLIST_SIZE_DESC",
						"specific":{
							"placeholder":"180"
						}
					}
				}
			},

			"cloudfront_subfield":{
				"type":"subfield",
				"path":"elements:'.$el_type.'\/params\/cloudfront.php"
			}

		}
	}

}}';