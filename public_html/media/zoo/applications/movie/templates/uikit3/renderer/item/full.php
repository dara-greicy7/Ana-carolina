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

<?php if ($this->checkPosition('top')) : ?>
	<?php echo $this->renderPosition('top', array('style' => 'uikit_block')); ?>
<?php endif; ?>
<div class="uk-grid" uk-grid>

	<div class="uk-width-3-4@m <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-flex-last@m'; ?>">

		<?php if ($this->checkPosition('title')) : ?>
		<h1 class="uk-h1"><?php echo $this->renderPosition('title'); ?></h1>
		<?php endif; ?>

		<?php if ($this->checkPosition('meta')) : ?>
		<p class="uk-text-muted">
			<?php echo $this->renderPosition('meta', array('style' => 'comma')); ?>
		</p>
		<?php endif; ?>

		<?php if ($this->checkPosition('description')) : ?>
			<?php echo $this->renderPosition('description', array('style' => 'uikit_blank')); ?>
		<?php endif; ?>

		<?php if ($this->checkPosition('specification')) : ?>
		<ul class="uk-list">
			<?php echo $this->renderPosition('specification', array('style' => 'uikit_list')); ?>
		</ul>
		<?php endif; ?>

		<?php if ($view->params->get('template.show_spoiler_warning')) : ?>
		<p class="uk-alert uk-alert-warning"><?php echo '<strong>'.JText::_('Warning').'</strong> '.JText::_('The following summary contains spoilers'); ?></p>
		<?php endif; ?>

		<?php if ($this->checkPosition('summary')) : ?>
			<?php echo $this->renderPosition('summary', array('style' => 'uikit_blank')); ?>
		<?php endif; ?>

	</div>

	<?php if ($this->checkPosition('sidebar')) : ?>
	<div class="uk-width-1-4@m <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-flex-first@m'; ?>">
		<?php echo $this->renderPosition('sidebar', array('style' => 'uikit_panel')); ?>
	</div>
	<?php endif; ?>

</div>

<?php if ($this->checkPosition('bottom')) : ?>
	<?php echo $this->renderPosition('bottom', array('style' => 'uikit_block')); ?>
<?php endif;
