<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="uk-card">
<?php if ($item) : ?>
	<?php echo $this->renderer->render('item.teaser', array('view' => $this, 'item' => $item)); ?>
<?php endif; ?>
</div>
