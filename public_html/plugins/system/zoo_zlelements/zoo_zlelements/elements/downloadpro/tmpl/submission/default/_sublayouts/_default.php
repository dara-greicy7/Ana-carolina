<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

	// load assets
	$this->app->document->addScript('elements:downloadpro/tmpl/submission/default/_sublayouts/_default/submission.min.js');

	// init vars
	$upload = $this->get('file');

	// is uploaded file
	$upload = is_array($upload) ? '' : $upload;

	if (!empty($upload)) {
		$upload = basename($upload);
	}

	$exist = (int) file_exists(JPATH_SITE.'/'.$this->get('file'));
?>

<div class="download-element">

	<div class="download-select">

		<div class="upload">
			<input class="filename"  type="text" id="filename<?php echo $this->identifier; ?>" readonly="readonly" />
			<div class="button-container">
				<button class="button-grey search" type="button"><?php echo JText::_('PLG_ZLFRAMEWORK_SEARCH'); ?></button>
				<input type="file" name="elements_<?php echo $this->identifier; ?>[]" />
			</div>
		</div>

		<input type="hidden" data-exist="<?php echo $exist; ?>" class="upload" name="<?php echo $this->getControlName('upload'); ?>" value="<?php echo ($upload ? $this->index() : ''); ?>" />
		<input type="hidden" class="file"  name="<?php echo $this->getControlName('file'); ?>" value="<?php echo $this->get('file'); ?>">
    </div>

    <div class="download-preview hidden">
        <span class="preview"><?php echo $upload; ?></span>
        <span class="download-cancel" title="<?php echo JText::_('PLG_ZLFRAMEWORK_REMOVE'); ?>"></span>
    </div>
	
	<script type="text/javascript">
	jQuery(function($) {
		$('#<?php echo $this->identifier; ?>').DownloadProSubmissionDefault();
	});
	</script>

</div>