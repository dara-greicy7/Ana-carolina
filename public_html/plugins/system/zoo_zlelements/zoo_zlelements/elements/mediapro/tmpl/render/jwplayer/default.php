<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// load assets
$this->app->document->addScript('elements:mediapro/assets/players/jwplayer/jwplayer.js');

// init vars
$result		= array();
$playlist	= array();
$media_list = $this->getRenderedValues($params);
$cfront 	= $params->find('layout._cloudfront');
$autoplay 	= $params->find('layout._autoplay');

// render media
foreach ($media_list['result'] as $index => $media_obj)
{
	// set the element data to it's file index
	$this->seek($index);

	// init vars
	$id = uniqid();
	$media = array();
	$width = $this->get('width');
	$height = $this->get('height');

	// if not overrided, use values from position
	$width = $width ? $width : $params->find('layout._width');
	$height = $height ? $height : $params->find('layout._height');

	// make sure width/height is set
	$width = $width ? $width : 300;
	$height = $height ? $height : 200;

	switch ($this->getVideoFormat($media_obj->getURL())) {
		case 'vimeo':
		case 'youtube':
		case 'youtu.be':
			if (!$params->find('layout._playlist', 0)) {
				$result[] = $this->renderEmbedURL($width, $height, $media_obj->getURL());
			} else {
				$media['file'] = $media_obj->getURL();
				$media['title'] = $media_obj->getTitle($this->get('title'));
				$playlist[] = $media;
			}
			break;

		case 'swf':
			if (!$params->find('layout._playlist', 0)) {
				$this->app->document->addScript('elements:media/assets/js/swfobject.min.js');
				$result[] = "<div id=\"$id\">
							<p><a href=\"http://www.adobe.com/go/getflashplayer\"><img src=\"http://www.adobe.com/images/shared/download_buttons/get_adobe_flash_player.png\" alt=\"Get Adobe Flash player\" /></a></p>
						</div>
						<script type=\"text/javascript\">
							swfobject.embedSWF(\"{$media_obj->getURL()}\", \"$id\", \"$width\", \"$height\", \"7.0.0\", \"\", {}, {allowFullScreen:\"true\", wmode: \"transparent\", play:\"$autoplay\" });
						</script>";
			}
			break;

		default: // case file

			if ($cfront && $this->config->find('files._s3')) {
				$distribution  = $params->find('layout._cloudfront_distribution');
				$file		   = $this->getCFSignedUrl($params, $media_obj->getPathname());
				$media['file'] = "rtmp://$distribution.cloudfront.net/cfx/st/{$media_obj->getExtension()}:$file";
			} else {
				$media['file'] = $media_obj->getURL();
			}
				
			$media['title'] 	= $media_obj->getTitle($this->get('title'));
			$media['image'] 	= $this->getPreviewImg($params)->get('url');

			// if playlist save the media for later processing
			if ($params->find('layout._playlist', 0)) {
				$playlist[] = $media;

			} else {
				$playlist[] = $media;

				// process media
				if ($layout = $this->getLayout("render/jwplayer/default/layout.php")) {
					$result[] = $this->renderLayout($layout, compact('params', 'id', 'playlist', 'width', 'height'));
				}
			}
	}
}

// processs playlist now
if ($params->find('layout._playlist', 0)) {
	if ($layout = $this->getLayout("render/jwplayer/default/layout.php")) {
		$result[] = $this->renderLayout($layout, compact('params', 'id', 'playlist', 'width', 'height'));
	}
}

// set the separator	
$separator = $params->find('separator._by_custom') != '' ? $params->find('separator._by_custom') : $params->find('separator._by');

// apply separator
$result = $this->app->zlfw->applySeparators($separator, $result, $params->find('separator._class'), $params->find('separator._fixhtml'));

// replace short codes and display
echo $this->app->zlfw->replaceShortCodes($result, array('item' => $this->_item));