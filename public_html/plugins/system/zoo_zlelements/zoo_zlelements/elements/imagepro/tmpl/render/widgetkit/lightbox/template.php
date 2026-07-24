<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

	$widget_id  = $widget->id.'-'.uniqid();
	$settings  	= $widget->settings;
	
	// get lightbox settings and remove them
	$lbsettings	= $settings['lightbox_settings'];
	unset($settings['lightbox_settings']);

	// create the settings object
	$settings = $this->app->data->create($settings);
	
	$bigimg = $this->getRenderedValues($params, $widget->mode, array('file2' => true, 'width' => $settings->get('width'), 'height' => $settings->get('height'), 'avoid_cropping' => $settings->get('avoid_cropping')));
	$bigimg = $bigimg['result'];
	$images = $this->getRenderedValues($params, $widget->mode); // get size from element specific
	$images = $images['result'];

	// if images is folder and lightbox is not
	if(count($images) > count($bigimg)){
		$bigimg = $this->getRenderedValues($params, $widget->mode, array('file2' => false, 'width' => $settings->get('width'), 'height' => $settings->get('height'), 'avoid_cropping' => $settings->get('avoid_cropping')));
		$bigimg = $bigimg['result'];		
	}

	$lightbox_options = '';
	if(isset($lbsettings['lightbox_overide'])) foreach($lbsettings as $name => $value){
		$lightbox_options .= "$name:$value;";
	};

	$result = array();

?>

<?php if (count($images)) : ?>

	<?php foreach ($images as $key => $image) : ?>
	
		<?php
		
			$lightbox = $overlay = $spotlight = '';

			/* Prepare Caption */
			$caption = strlen($image['caption']) ? strip_tags($image['caption']) : $image['filename'];

			/* Prepare Lightbox */
			if (!$params->find('specific._link_to_item')) // no compatible with item link
			{
				// set dimensions
				$lightbox_options .= $settings->get('width') && $settings->get('width') != 'auto' ? 'width:'.$settings->get('width').';' : '';
				$lightbox_options .= $settings->get('height') && $settings->get('width') != 'auto' ? 'height:'.$settings->get('height').';' : '';

				$lightbox = 'data-lightbox="group:'.$widget_id.';'.$lightbox_options.'"';

				// override caption options
				$lbc = $caption;
				if($settings->get('lightbox_caption') == 0){
					$lbc = '';
				} elseif($settings->get('lightbox_caption') == 2 && $title = $settings->get('_lightbox_custom_title')){
					$lbc = $title;
				}
	
				if (strlen($lbc)) $lightbox .= ' title="'.$lbc.'"';
			}

			/* Prepare Spotlight */
			if ($settings->get('spotlight')) 
			{
				$sl_caption = $caption; // set spotlight caption

				// override caption
				if($settings->get('captions') == 0){
					$sl_caption = '';
				} elseif($settings->get('captions') == 2 && $title = $settings->get('custom_caption')){
					$sl_caption = $title;
				}

				$spotlight_opts  = $settings->get('spotlight_effect') ? 'effect:'.$settings->get('spotlight_effect') : '';
				$spotlight_opts .= $settings->get('spotlight_duration') ? ';duration:'.$settings->get('spotlight_duration') : '';

				if (strlen($spotlight_opts) && strlen($caption)) {
					$spotlight = 'data-spotlight="'.$spotlight_opts.'"';
					$overlay = '<div class="overlay">'.$sl_caption.'</div>';
				} elseif (!$settings['spotlight_effect']) {
					$spotlight = 'data-spotlight="on"';
				}
			}

			
			/* Prepare Image */
			$title = $image['title'] ? ' title="'.$image['title'].'"' : '';
			$content = '<img src="'.$image['fileurl'].'" width="'.$image['width'].'" height="'.$image['height'].'" alt="'.$image['alt'].'"'.$title.' />'.$overlay;

			$result[] = '<a href="'.($this->config->find('specific._custom_link') && $bigimg[$key]['link'] ? $bigimg[$key]['link'] : $bigimg[$key]['fileurl']).'" '.$lightbox.' '.$spotlight.'>'.$content.'</a>';

		?>
	
	<?php endforeach; ?>
	
	<?php echo $this->app->zlfw->applySeparators($params->find('separator._by'), $result, $params->find('separator._class')); ?>
	
<?php else : ?>
	<?php echo "No images found."; ?>
<?php endif; ?>