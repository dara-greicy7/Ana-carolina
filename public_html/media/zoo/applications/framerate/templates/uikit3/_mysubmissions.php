<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

$this->app->document->addStylesheet($this->template->resource.'assets/css/submission.css');
$this->app->document->addScript('assets:js/submission.js');

$this->pagination_link = $this->app->route->mysubmissions($this->submission);

?>

<div id="mysubmissions">

	<?php if($this->show_add): ?>
	<div class="uk-button-dropdown">
		<a class="uk-button uk-button-default trigger" href="javascript:void(0);" title="<?php echo JText::_('Add Item'); ?>"><?php echo JText::_('Add Item'); ?> <span uk-icon="icon:triangle-down"></span></a>
		<div uk-dropdown="mode: click">
			<ul class="uk-nav uk-dropdown-nav">
			<?php foreach($this->types as $id => $type) : ?>
				<li>
					<?php $add_link = $this->app->route->submission($this->submission, $id, null, 0, 'mysubmissions'); ?>
					<a href="<?php echo JRoute::_($add_link); ?>" title="<?php echo sprintf(JText::_('Add %s'), $type->name); ?>"><?php echo $type->name; ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if (isset($this->lists['select_type'])) : ?>
	<form action="<?php echo JRoute::_($this->pagination->link($this->pagination_link, 'page='.$this->page)); ?>" method="post" name="adminForm" id="adminForm" accept-charset="utf-8">
		<?php echo $this->lists['select_type']; ?>
		<input class="uk-input" type="text" name="search" id="zoo-search" value="<?php echo $this->lists['search'];?>" />
		<button class="uk-button uk-button-default" onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
		<button class="uk-button uk-button-default" onclick="document.getElementById('zoo-search').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
	</form>
	<?php endif; ?>

	<?php if (count($this->items)) : ?>
	<ul class="uk-list uk-list-divider submissions">
		<?php foreach ($this->items as $id => $item) : ?>
		<li>

			<div class="uk-grid-small" uk-grid>
				<div class="uk-width-expand">

					<?php echo $item->name; ?> (<?php echo $item->getType()->name; ?>)

				</div>
				<div class="uk-width-auto">

					<?php $edit_link = $this->app->route->submission($this->submission, $item->type, null, $id, 'mysubmissions'); ?>
					<a class="uk-icon-link" href="<?php echo JRoute::_($edit_link); ?>" title="<?php echo JText::_('Edit Item'); ?>"><span uk-icon="icon:file-edit"></span></a>

				</div>
				<?php if ($this->submission->isInTrustedMode()) : ?>
				<div class="uk-width-auto">

					<a href="<?php echo $this->app->link(array('controller' => 'submission', 'submission_id' => $this->submission->id, 'task' => 'remove', 'item_id' => $id)); ?>" title="<?php echo JText::_('Delete Item'); ?>" class="uk-icon-link delete-item"><span uk-icon="icon:close"></span></a>

				</div>
				<?php endif; ?>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php else : ?>

		<?php if (empty($this->lists['search'])) : ?>
		<div class="uk-alert uk-alert-primary"><?php echo sprintf(JText::_('You have not submitted any %s items yet.'), $this->filter_type); ?></div>
		<?php else : ?>
		<div class="uk-alert uk-alert-primary"><?php echo JText::_('SEARCH_NO_ITEMS').'!'; ?></div>
		<?php endif; ?>

	<?php endif; ?>

	<?php echo $this->partial('pagination'); ?>

</div>

<script type="text/javascript">
	jQuery(function($) {
		$('#mysubmissions').SubmissionMysubmissions({ msgDelete: '<?php echo JText::_('SUBMISSION_DELETE_CONFIRMATION'); ?>' });
	});
</script>
