<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// include assets css
$this->app->document->addStylesheet('elements:downloadpro/assets/css/downloadpro.css');

if ($file) {
	
	if ($limit_reached) {
		echo '<a class="yoo-zoo element-download-button" href="javascript:alert(\''.JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT_REACHED').'\');" title="'.JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT_REACHED').'"><span><span>'.JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD').'</span></span></a>';
	} else {
		$target = $params->find('layout._target') ? ' target="_blank"' : '';
		echo '<a class="yoo-zoo element-download-button" href="'.JRoute::_($download_link).'" title="'.$download_name.'"'.$target.'><span><span>'.$download_name.'</span></span></a>';
	}
	
} else {
	echo JText::_('PLG_ZLELEMENTS_DWP_NO_FILE_SELECTED');
}
