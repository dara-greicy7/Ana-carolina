<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

if ($file) {
	
	if ($limit_reached) {
		echo JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT_REACHED');
	} else {
		$target = $params->find('layout._target') ? ' target="_blank"' : '';
		echo '<a href="'.JRoute::_($download_link).'" title="'.$download_name.'"'.$target.'>'.$download_name.'</a>';
	}
	
} else {
	echo JText::_('PLG_ZLELEMENTS_DWP_NO_FILE_SELECTED');
}
