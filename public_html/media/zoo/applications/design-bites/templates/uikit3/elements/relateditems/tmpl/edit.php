<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$this->app->html->_('behavior.modal', 'a.modal-button');

?>

<div id="<?php echo $this->identifier; ?>" class="select-relateditems">

	<ul class="uk-list">

	<?php foreach ($data as $item) : ?>

		<li>
			<div>
				<div class="uk-display-inline-block uk-text-middle item-name"><?php echo $item->name; ?></div>
				<span class="item-sort" title="<?php echo JText::_('Sort Item'); ?>"><span class="uk-icon" uk-icon="icon: triangle-down"></span></span>
				<span class="item-delete" title="<?php echo JText::_('Delete Item'); ?>"><span class="uk-icon" uk-icon="icon: trash"></span></span>
				<input type="hidden" name="<?php echo $this->getControlName('item', true); ?>" value="<?php echo $item->id; ?>"/>
			</div>
		</li>

	<?php endforeach; ?>
	</ul>

	<a class="uk-button uk-button-default uk-button-small modal-button" rel="{handler: 'iframe', size: {x: 850, y: 500}}" title="<?php echo JText::_('Add Item'); ?>" href="<?php echo $link; ?>" ><?php echo JText::_('Add Item'); ?></a>

</div>

<script type="text/javascript">
	jQuery(function($) {
		$('#<?php echo $this->identifier; ?>').ElementRelatedItems({ variable: '<?php echo $this->getControlName('item', true); ?>', msgDeleteItem: '<?php echo JText::_('Delete Item'); ?>', msgSortItem: '<?php echo JText::_('Sort Item'); ?>' });
	});
</script>
