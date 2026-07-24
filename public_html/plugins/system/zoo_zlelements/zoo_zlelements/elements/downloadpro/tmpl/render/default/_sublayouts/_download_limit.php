<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

if ($file) {
	
	$download_limit = ($download_limit) ? $download_limit : '-';
	echo $download_limit;
	
} else {
	echo JText::_('PLG_ZLELEMENTS_DWP_NO_FILE_SELECTED');
}
