<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="<?php echo $this->identifier; ?>">

	<div class="uk-margin">
		<div class="uk-display-block" uk-form-custom="target: true">
			<input type="file" name="elements_<?php echo $this->identifier; ?>" onchange="javascript: document.getElementById('filename<?php echo $this->identifier; ?>').value = this.value.replace(/^.*[\/\\]/g, '');">
			<input class="uk-input" type="text" placeholder="<?php echo JText::_('Pick File'); ?>" id="filename<?php echo $this->identifier; ?>" readonly>
		</div>
	</div>

	<?php if (isset($lists['upload_select'])) : ?>

		<?php echo $lists['upload_select']; ?>
		
	<?php else : ?>

		<input type="hidden" class="upload" name="<?php echo $this->getControlName('upload'); ?>" value="<?php echo $upload ? 1 : ''; ?>" />

	<?php endif; ?>

    <div class="uk-margin download-preview">
        <span class="preview"><?php echo $upload; ?></span>
        <span class="download-cancel" title="<?php JText::_('Remove file'); ?>"><span class="uk-icon" uk-icon="icon: trash"></span></span>
    </div>

    <?php if ($trusted_mode) : ?>

	<div class="more-options">

		<div class="trigger">
			<div>
				<div class="advanced button hide">Hide Options</div>
				<div class="advanced button">Show Options</div>
			</div>
		</div>

		<div class="advanced options">

			<div class="row download-limit">
				<?php echo $this->app->html->_('control.text', $this->getControlName('download_limit'), ($upload ? $this->get('download_limit') : ''), 'maxlength="255" title="'.JText::_('Download limit').'" placeholder="'.JText::_('Download limit').'"'); ?>
			</div>

		</div>
	</div>
    <?php endif; ?>

    <script type="text/javascript">
		jQuery(function($) {
			$('#<?php echo $this->identifier; ?>').DownloadSubmission();
		});
    </script>

</div>
