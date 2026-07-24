<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

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
	<?php if ($this->checkPosition('title') || $this->checkPosition('header')) : ?>

		<?php if ($this->checkPosition('header')) : ?>
		<div class="uk-align-right@m">
			<?php echo $this->renderPosition('header', array('style' => 'uikit_block')); ?>
		</div>
		<?php endif; ?>

		<?php if ($this->checkPosition('title')) : ?>
		<h1 class="uk-h1"><?php echo $this->renderPosition('title'); ?></h1>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($this->checkPosition('infobar')) : ?>
	<ul class="uk-subnav uk-subnav-divider">
		<?php echo $this->renderPosition('infobar', array('style' => 'uikit_list')); ?>
	</ul>
	<?php endif; ?>

<div class="uk-card uk-card-default uk-card-body">
<?php if ($this->checkPosition('media') || $this->checkPosition('ingredients')) : ?>

	<?php if ($this->checkPosition('media')) : $alignment = $view->params->get('template.item_media_alignment'); ?>
	<div class="<?php echo 'uk-align-'.$alignment.($alignment == "left" || $alignment == "right" ? '@m' : ''); ?>">
		<?php echo $this->renderPosition('media'); ?>
	</div>
	<?php endif; ?>

	<div class="uk-overflow-hidden">
	<?php if ($this->checkPosition('ingredients')) : ?>
		<?php echo $this->renderPosition('ingredients', array('style' => 'uikit_blank_panel')); ?>
	<?php endif; ?>
	</div>

<?php endif; ?>
</div>

<div class="uk-margin">

	<div class="uk-grid" uk-grid>

		<div class="uk-width-3-4@m <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-flex-last@m'; ?>">

			<?php if ($this->checkPosition('directions')) : ?>
				<?php echo $this->renderPosition('directions', array('style' => 'uikit_blank')); ?>
			<?php endif; ?>

		</div>

	<?php if ($this->checkPosition('sidebar') || $this->checkPosition('directions')) : ?>
		<?php if ($this->checkPosition('sidebar')) : ?>
		<div class="uk-width-1-4@m <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-flex-first@m'; ?>">
			<?php echo $this->renderPosition('sidebar', array('style' => 'uikit_panel')); ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	</div>

</div>

<?php if ($this->checkPosition('bottom')) : ?>
	<?php echo $this->renderPosition('bottom', array('style' => 'uikit_block')); ?>
<?php endif;
