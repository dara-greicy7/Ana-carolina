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

<div class="<?php echo $this->identifier; ?>">

	<div class="uk-margin">
		<div class="uk-display-block" uk-form-custom="target: true">
			<input type="file" name="elements_<?php echo $this->identifier; ?>" onchange="javascript: document.getElementById('filename<?php echo $this->identifier; ?>').value = this.value.replace(/^.*[\/\\]/g, '');">
			<input class="uk-input" type="text" placeholder="<?php echo JText::_('Pick File'); ?>" id="filename<?php echo $this->identifier; ?>" readonly>
		</div>
	</div>

	<?php if (isset($lists['image_select'])) : ?>

		<?php echo $lists['image_select']; ?>

	<?php else : ?>

		<input type="hidden" class="image" name="<?php echo $this->getControlName('image'); ?>" value="<?php echo $image ? 1 : ''; ?>">

	<?php endif; ?>

	<div class="uk-height-medium uk-position-relative uk-margin image-preview">
		<img class="uk-responsive-height" src="<?php echo $image; ?>" alt>
		<span class="uk-position-top-right uk-position-small image-cancel" title="<?php JText::_('Remove image'); ?>"><span class="uk-icon" uk-icon="icon: trash"></span></span>
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

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('title'), $this->get('title'), 'maxlength="255" title="'.JText::_('Title').'" placeholder="'.JText::_('Title').'"'); ?>
			</div>

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('link'), $this->get('link'), 'size="60" maxlength="255" title="'.JText::_('Link').'" placeholder="'.JText::_('Link').'"'); ?>
			</div>

			<div class="row">
				<span><?php echo JText::_('New window'); ?></span>
				<div class="uk-display-inline-block"><?php echo $this->app->html->_('select.booleanlist', $this->getControlName('target'), $this->get('target'), $this->get('target')); ?></div>
			</div>

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('rel'), $this->get('rel'), 'size="60" maxlength="255" title="'.JText::_('Rel').'" placeholder="'.JText::_('Rel').'"'); ?>
			</div>

		</div>

	</div>
	<?php endif; ?>

</div>

<script type="text/javascript">
	jQuery(function($) {
		$('#item-submission .<?php echo $this->identifier; ?>').ImageSubmission({ uri: '<?php echo JURI::root(); ?>' });
	});
</script>
