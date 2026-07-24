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
			"text":"Default Layout",
			"big":1
		},

		"layout_wrapper":{
			"type":"subfield",
			"path":"elements:'.$element->getElementType().'\/params\/image.php",
			"arguments":{
				"params":{
					"link":"true"
				}
			},
			"adjust_ctrl":{
				"pattern":'.json_encode('/layout/').',
				"replacement":"specific"
			}
		}
		'.($this->element->config->find('specific._custom_lightbox', 0) ? ', "_lightbox":{
			"type": "checkbox",
			"label": "PLG_ZLELEMENTS_IMGP_ENABLE_LIGHTBOX",
			"help": "PLG_ZLELEMENTS_IMGP_ENABLE_LIGHTBOX_DESC",
			"specific": {
				"label":"JYES"
			},
			"dependents":"_link_to_item !> 1 | main_wrapper_lightbox > 1 | _default_lightbox_caption > 1"
		}' : '').'
		'.($this->element->config->find('specific._custom_lightbox', 0) ? ', "main_wrapper_lightbox":{
			"type": "wrapper",
			"fields": {
				"_lightbox_width":{
					"type": "text",
					"label": "PLG_ZLELEMENTS_IMGP_RESIZE_LIGHTBOX_IMAGE_WIDTH",
					"help": "PLG_ZLELEMENTS_IMGP_RESIZE_LIGHTBOX_IMAGE_WIDTH_DESC"
				},
				"_lightbox_height":{
					"type": "text",
					"label": "PLG_ZLELEMENTS_IMGP_RESIZE_LIGHTBOX_IMAGE_HEIGHT",
					"help": "PLG_ZLELEMENTS_IMGP_RESIZE_LIGHTBOX_IMAGE_HEIGHT_DESC"
				}
			}
		}' : '').'
		'.($this->element->config->find('specific._custom_lightbox', 0) ? ', "_default_lightbox_caption":{
			"type": "text",
			"label": "PLG_ZLELEMENTS_IMGP_DEFAULT_LIGHTBOX_TITLE",
			"help": "PLG_ZLELEMENTS_IMGP_DEFAULT_LIGHTBOX_TITLE_DESC",
			"default": "{title|filename}"
		}' : '').'
		'.(($this->element->config->find('specific._custom_overlay', 0) || $this->element->config->find('specific._custom_spotlight', 0)) ? ', "_overlay":{
			"type": "checkbox",
			"label": "PLG_ZLELEMENTS_IMGP_ENABLE_OVERLAY",
			"help": "PLG_ZLELEMENTS_IMGP_ENABLE_OVERLAY_DESC",
			"specific": {
				"label":"JYES"
			},
			"dependents":"_default_overlay_caption > 1"
		}' : '').'
		'.(($this->element->config->find('specific._custom_overlay', 0) || $this->element->config->find('specific._custom_spotlight', 0)) ? ', "_default_overlay_caption":{
			"type": "text",
			"label": "PLG_ZLELEMENTS_IMGP_DEFAULT_OVERLAY_TITLE",
			"help": "PLG_ZLELEMENTS_IMGP_DEFAULT_OVERLAY_TITLE_DESC",
			"default": "{title|filename}"
		}' : '').'

	}}';