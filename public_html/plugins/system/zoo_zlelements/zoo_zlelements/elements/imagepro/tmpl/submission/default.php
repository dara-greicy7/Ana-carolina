<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

	// load assets
	$this->app->document->addScript('elements:imagepro/tmpl/submission/default/submission.js');

	// init vars
	$image = $this->get('file');

	// is uploaded file
	$image = is_array($image) ? array_shift($image) : $image;
	
	if (!empty($image)) {
		$image = $this->app->zlfw->resizeImage($this->app->path->path("root:$image"), 0, 0);
		$image = $this->app->path->relative($image);
		$image = $this->app->path->url("root:$image");
	}

	$exist = file_exists(JPATH_SITE.'/'.$this->get('file'));
?>

<div class="image-element">

	<div class="image-select">

		<div class="upload">
		
			<input class="filename" type="text" id="filename<?php echo $this->identifier; ?>" readonly="readonly" />
			<div class="button-container">
				<button class="button-grey search" type="button"><?php echo JText::_('PLG_ZLFRAMEWORK_SEARCH'); ?></button>
				<input type="file" name="elements_<?php echo $this->identifier; ?>[]" />
			</div>
		</div>
		
		<input type="hidden" class="image" name="<?php echo $this->getControlName('image'); ?>" value="<?php echo ($image ? $this->index() : ''); ?>">

		<input type="hidden" class="file" data-exist="<?php echo (int) $exist; ?>" name="<?php echo $this->getControlName('file'); ?>" value="<?php echo $this->get('file'); ?>">
	</div>

	<div class="image-preview">
		<img src="<?php echo $image; ?>" alt="preview">
		<span class="image-cancel" title="<?php echo JText::_('PLG_ZLFRAMEWORK_REMOVE'); ?>"></span>
	</div>

	<script type="text/javascript">
	jQuery(function($) {
		$('#<?php echo $this->identifier; ?>').ImageSubmissionpro();
	});
	</script>

</div>