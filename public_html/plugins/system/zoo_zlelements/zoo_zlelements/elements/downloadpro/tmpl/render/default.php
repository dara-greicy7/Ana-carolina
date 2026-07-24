<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$files = $this->getRenderedValues($params);
$result = array();

// render media
foreach ($files['result'] as $file)
{
	$index = $file['index'];
	$file_index = $file['file_index'];
	$file  = $file['file'];

	// set the element index
	$this->seek($index);

	$filename 	 = $file->getFilename();
	$title		 = $file->getTitle($this->get('title'));
	$fileDetails = $this->app->data->create($this->getFileDetails($file));
	$down_name	 = htmlentities($this->app->string->str_ireplace(array('{filename}', '{title}'), array($filename, $title), $params->find('specific._download_name', '')));

	// render layout
	$sub_layout  = $params->find('layout._sublayout', '_default.php');
	if($layout = $this->getLayout("render/default/_sublayouts/$sub_layout"))
	{
		$result[] = $this->renderLayout($layout,
			array(
				'file' 				=> $file,
				'filename' 			=> $filename,
				'size' 				=> $fileDetails->get('size'),
				'hits' 				=> $this->get('hits', 0),
				'download_name'		=> $down_name,
				'download_link'		=> $this->getLink($params, $file, $file_index),
				'filetype' 			=> JFile::getExt($filename),
				'display' 			=> $params->find('specific._display', null),
				'limit_reached' 	=> $this->isDownloadLimitReached(),
				'download_limit' 	=> $this->get('download_limit'),
				'params'			=> $params
			)
		);
	}
}


// set separator
$separator = $params->find('separator._by_custom') != '' ? $params->find('separator._by_custom') : $params->find('separator._by');

// render
$result = $this->app->zlfw->applySeparators($separator, $result, $params->find('separator._class'), $params->find('separator._fixhtml'));

echo $this->app->zlfw->replaceShortCodes($result, array('item' => $this->_item));
?>