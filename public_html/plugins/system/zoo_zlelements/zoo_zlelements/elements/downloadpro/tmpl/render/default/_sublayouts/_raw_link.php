<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

if ($file) {
	
	echo JRoute::_($download_link);
	
} else {
	echo JText::_('PLG_ZLELEMENTS_DWP_NO_FILE_SELECTED');
}
