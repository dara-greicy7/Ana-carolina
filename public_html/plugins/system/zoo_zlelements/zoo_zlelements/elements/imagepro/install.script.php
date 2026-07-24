<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

if($type == 'install' || $type == 'update')
{
	if($type == 'install') {
		echo '<p><strong>Image Pro</strong> Element installed succesfully.</p>';
	} else {

		// Remove depricated folders
		jimport('joomla.filesystem.file');
		$f = JPATH_ROOT.'/plugins/system/zoo_zlelements/zoo_zlelements/elements/imagepro/tmpl'; // even if removed will be repopulated with the new content
		if(JFolder::exists($f))	JFolder::delete($f);

		echo '<p><strong>Image Pro</strong> Element updated succesfully.</p>';
	}
}
else if($type == 'uninstall')
{
	
}
