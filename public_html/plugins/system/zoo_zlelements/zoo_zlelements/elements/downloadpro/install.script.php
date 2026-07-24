<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/* List of obsolete files and folders */
$obsolete = array(
	'files'	=> array(
		'plugins/system/zoo_zlelements/zoo_zlelements/elements/downloadpro/tmpl/edit/edit.php',
		'plugins/system/zoo_zlelements/zoo_zlelements/elements/downloadpro/tmpl/edit/_edit.php'
	),
	'folders' => array(
	)
);

if($type == 'install' || $type == 'update')
{
	if($type == 'install') {
		echo '<p><strong>Download Pro</strong> Element installed succesfully.</p>';
	} else {
		echo '<p><strong>Download Pro</strong> Element updated succesfully.</p>';
	}

	/* Removes obsolete files and folders */
	if(!empty($obsolete['files'])) foreach($obsolete['files'] as $file) {
		$f = JPATH_ROOT.'/'.$file;
		if(!JFile::exists($f)) continue;
		JFile::delete($f);
	}

	if(!empty($obsolete['folders'])) foreach($obsolete['folders'] as $folder) {
		$f = JPATH_ROOT.'/'.$folder;
		if(!JFolder::exists($f)) continue;
		JFolder::delete($f);
	}
}
else if($type == 'uninstall') {}