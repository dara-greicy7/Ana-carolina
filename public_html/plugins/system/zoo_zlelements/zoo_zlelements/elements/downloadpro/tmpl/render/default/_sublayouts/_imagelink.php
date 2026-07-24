<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// include assets css
$this->app->document->addStylesheet('elements:downloadpro/tmpl/render/default/_sublayouts/_imagelink/sets/'.$params->find('layout.sublayout._set', 'default').'/style.css');

if ($file) {
	
	if ($limit_reached) {
		echo '<div class="zl-zoo element-download-type element-download-type-'.$filetype.'" title="'.JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT_REACHED').'"></div>';
	} else {
		$target = $params->find('layout._target') ? ' target="_blank"' : '';
		echo '<a class="zl-zoo element-download-type element-download-type-'.$filetype.'" href="'.JRoute::_($download_link).'" title="'.$download_name.'"'.$target.'></a>';
	}
	
} else {
	echo JText::_('PLG_ZLELEMENTS_DWP_NO_FILE_SELECTED');
}
