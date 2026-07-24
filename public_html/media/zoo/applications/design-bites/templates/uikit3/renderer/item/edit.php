<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php if ($this->checkPosition('fields')) : ?>
<fieldset class="uk-fieldset">

	<?php echo $this->renderPosition('fields', array('style' => 'submission.uikit_row')); ?>

</fieldset>
<?php endif; ?>

<?php if ($this->checkPosition('administration')) : ?>
<fieldset class="uk-fieldset uk-margin-medium-top">
	<legend class="uk-legend"><?php echo JText::_('Administration'); ?></legend>

	<?php echo $this->renderPosition('administration', array('style' => 'submission.uikit_row')); ?>

</fieldset>
<?php endif;
