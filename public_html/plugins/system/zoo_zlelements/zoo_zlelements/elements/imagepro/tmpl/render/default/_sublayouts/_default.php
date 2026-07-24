<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// add zx to closest parent
if (!defined('IMAGEPRO_ELEMENT_ZX_SCRIPT_DECLARATION')) {
	define('IMAGEPRO_ELEMENT_ZX_SCRIPT_DECLARATION', true);
	$this->app->document->addScriptDeclaration(
		"jQuery(function($) {
			$('.zx').closest('div').addClass('zx');
		});"
	);
}

// init vars
$widgetkit = JFile::exists(JPATH_ADMINISTRATOR.'/components/com_widgetkit/widgetkit.php');
$link_enabled = $params->find('specific._link_to_item');
$lightbox_enabled = $params->find('layout._lightbox') && $this->config->find('specific._custom_lightbox', 0);
// 'specific._custom_spotlight' is DEPRECATED
$overlay_enabled = ($params->find('layout._overlay') || $params->find('layout._spotlight')) && ($this->config->find('specific._custom_overlay', 0) || $this->config->find('specific._custom_spotlight', 0));

if ($lightbox_enabled || $overlay_enabled) {
	$this->app->zlfw->zlux->loadMainAssets(true);
	$this->app->document->addScript('zlmedia:vendor/uikit/js/components/lightbox.min.js');
}

// set link attributes
$link_attr['target'] = $img['target'] ? 'target="_blank"' : '';
if ($widgetkit) {
	$link_attr['rel']	= $img['rel'] ? 'data-lightbox="'.$img['rel'].'"' : '';
} else {
	$link_attr['rel']	= $img['rel'] ? 'rel="'.$img['rel'].'"' : '';
}
$link_attr['title']	= $img['lightbox_caption'] ? 'title="'.$img['lightbox_caption'].'"' : '';

if ($lightbox_enabled) {
	$lightbox_resize_width  = $params->find('layout._lightbox_height', 0);
	$lightbox_resize_height = $params->find('layout._lightbox_width', 0);
	$img['link'] = JURI::root() . $this->app->path->relative($this->app->zoo->resizeImage(JPATH_ROOT.'/'.$img['lightbox_image'], $lightbox_resize_width , $lightbox_resize_height));
}

// set img attributes
$img_attr = array();
$img_attr[] = 'src="'.$img['fileurl'].'"';
$img_attr[] = 'alt="'.$img['alt'].'"';
$img_attr[] = 'width="'.$img['width'].'"';
$img_attr[] = 'height="'.$img['height'].'"';
$img_attr[] = $img['title'] ? 'title="'.$img['title'].'"' : '';

// overlay
$overlay = '';
$overlay_wrapper_class = 'class="zx"';

if (strlen($img['overlay_effect']) && $overlay_enabled) {
	$overlay_wrapper_class = 'class="zx uk-overlay uk-overlay-hover"';
	if ($img['overlay_effect'] != 'default') {
		$caption = strip_tags($img['caption']);
		$overlay = '<div class="uk-overlay-panel uk-overlay-background uk-overlay-'.$img['overlay_effect'].' uk-overlay-slide-'.$img['overlay_effect'].'">'.$caption.'</div>';
	} else {
		$overlay = '<div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-overlay-icon"></div>';
	}
}

// remove empty values
$link_attr = array_filter($link_attr);
$img_attr = array_filter($img_attr);

// set img
$content = '<img '.implode(' ', $img_attr).' />'.$overlay;

?>

<?php if ($link_enabled || $lightbox_enabled) : ?>
	<a <?php echo $overlay_wrapper_class; ?> href="<?php echo JRoute::_($img['link']); ?>" <?php echo implode(' ', $link_attr); ?><?php echo $lightbox_enabled ? ' data-uk-lightbox="{group:\''.$this->identifier.'\'}" ' : ''; ?>>
		<?php echo $overlay; ?>
		<?php echo $content; ?>
	</a>
<?php elseif ($overlay_enabled) : ?>
	<div <?php echo $overlay_wrapper_class; ?>>
		<?php echo $overlay; ?>
		<?php echo $content; ?>
	</div>
<?php else : ?>
	<?php echo $content; ?>
<?php endif;
