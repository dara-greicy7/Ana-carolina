<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php if ($this->checkPosition('title')) : ?>
<h2>
	<?php echo $this->renderPosition('title'); ?>
</h2>
<?php endif; ?>

<?php if ($this->checkPosition('description')) : ?>
	<?php echo $this->renderPosition('description'); ?>
<?php endif; ?>

<?php if ($this->checkPosition('links')) : ?>
<ul class="uk-subnav uk-subnav-divider">
	<?php echo $this->renderPosition('links', array('style' => 'uikit_subnav')); ?>
</ul>
<?php endif;
