<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$cfront 	= $params->find('layout._cloudfront', '');
$autoplay 	= $params->find('layout._autoplay', 0);
$preload 	= $params->find('layout._preload', 'none');

// set attributes
$width_attr  = $width ? ' width="'.$width.'"' : '';
$height_attr = $height && $playlist[0]['ext'] != 'mp3' ?  ' height="'.$height.'"' : '';
$autoplay 	 = $autoplay ? ' autoplay="autoplay"' : '';
$poster 	 = $playlist[0]['poster'] ? ' poster="'.$playlist[0]['poster'].'"' : '';
$preload	 = ' preload="'.$preload.'"';

// set image preview
$image = $this->getPreviewImg($params);

echo '<'.$playlist[0]['tag'].' class="zl-element-mediapro" src="'.$playlist[0]['src'].'"'.$poster.$width_attr.$height_attr.$autoplay.$preload.$playlist[0]['type'].'></'.$playlist[0]['tag'].'>';