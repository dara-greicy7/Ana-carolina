<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$info = getimagesize($this->app->path->path($img['sourcefile']));
$link_enabled = !empty($img['link']);

// set link attributes
$link_attr['target'] = $img['target'] ? 'target="_blank"' : '';
$link_attr['rel']	= $img['rel'] ? 'rel="'.$img['rel'].'"' : '';
$link_attr['title']	= $img['title'] ? 'title="'.$img['title'].'"' : '';

// set img attributes
$img_attr = array();
$img_attr[] = 'src="'.$img['sourceurl'].'"';
$img_attr[] = 'alt="'.$img['alt'].'"';
$img_attr[] = $info[3]; // width/height
$img_attr[] = $link_attr['title'];

// remove empty values
$link_attr = array_filter($link_attr);
$img_attr = array_filter($img_attr); 

$content = '<img '.implode(' ', $img_attr).' />';

if ($link_enabled) {
	echo '<a href="'.JRoute::_($img['link']).'" '.implode(' ', $link_attr).'>'.$content.'</a>';
} else {
	echo $content;
}