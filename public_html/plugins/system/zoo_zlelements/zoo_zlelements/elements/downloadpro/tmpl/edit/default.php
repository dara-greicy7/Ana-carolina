<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

$link = $this->app->link(array('controller' => 'zlframework', 'task' => 'callelement', 'format' => 'raw', 'type' => $this->_item->getType()->id, 'item_id' => $this->_item->id, 'elm_id' => $this->identifier), false);
$flashUrl = $this->app->path->url('elements:filespro/assets/plupload/plupload.flash.swf');

?>

<?php if ($this->config->get('repeatable')) : ?>

	<div id="<?php echo $this->identifier; ?>" class="repeat-elements filespro zl">
		<ul class="repeatable-list">

			<?php $this->rewind(); ?>
			<?php foreach($this as $self) : ?>
				<li class="repeatable-element">
					<?php echo $this->_edit(); ?>
				</li>
			<?php endforeach; ?>

			<?php $this->rewind(); ?>

			<li class="repeatable-element hidden">
				<?php echo preg_replace('/(elements\[\S+])\[(\d+)\]/', '$1[-1]', $this->_edit()); ?>
			</li>

		</ul>
		<p class="add">
			<a class="zl-btn" href="javascript:void(0);"><?php echo JText::_('PLG_ZLFRAMEWORK_ADD_INSTANCE'); ?></a>
		</p>
	</div>

	<script type="text/javascript">
		jQuery('#<?php echo $this->identifier; ?>').ElementRepeatablePro({ msgDeleteElement : '<?php echo JText::_('PLG_ZLFRAMEWORK_DELETE_ELEMENT'); ?>', msgSortElement : '<?php echo JText::_('PLG_ZLFRAMEWORK_SORT_ELEMENT'); ?>', msgLimitReached : '<?php echo JText::_('PLG_ZLFRAMEWORK_INSTANCE_LIMIT_REACHED'); ?>', instanceLimit: '<?php echo $this->config->get('instancelimit') ?>' });
		jQuery('#<?php echo $this->identifier; ?>').ElementFilespro({
			url: '<?php echo $link ?>', 
			flashUrl: '<?php echo $flashUrl; ?>',
			type: 'download', 
			fileMode: '<?php echo $this->config->find('files._mode', 'files') ?>',
			max_file_size: '<?php echo ($this->config->find('files._max_upload_size')!='') ? filter_var($this->config->find('files._max_upload_size', '1024'), FILTER_SANITIZE_NUMBER_INT) : '1024' ?>kb',
			extensions: '<?php echo $this->getLegalExtensions(','); ?>',
			title: '<?php echo htmlspecialchars($this->config->get('name'), ENT_QUOTES) ?>',
			filemanager: <?php echo $this->config->find('files._s3', 0) ? 'false' : 'true' ?>,
			resize: <?php echo json_encode($this->config->find('files.resize', array())); ?>
		});
	</script>
	
<?php else : ?>

	<div id="<?php echo $this->identifier; ?>" class="repeatable-element filespro single zl">
		<?php echo $this->_edit(); ?>
	</div>

	<script type="text/javascript">
		jQuery('#<?php echo $this->identifier; ?>').ElementFilespro({
			url: '<?php echo $link ?>', 
			fileMode: 'both', 
			flashUrl: '<?php echo $flashUrl; ?>',
			type: 'download', 
			fileMode: '<?php echo $this->config->find('files._mode', 'files') ?>',
			max_file_size: '<?php echo ($this->config->find('files._max_upload_size')!='') ? filter_var($this->config->find('files._max_upload_size', '1024'), FILTER_SANITIZE_NUMBER_INT) : '1024' ?>kb',
			extensions: '<?php echo $this->getLegalExtensions(','); ?>',
			title: '<?php echo htmlspecialchars($this->config->get('name'), ENT_QUOTES) ?>',
			filemanager: <?php echo $this->config->find('files._s3', 0) ? 'false' : 'true' ?>,
			resize: <?php echo json_encode($this->config->find('files.resize', array())); ?>
		});
	</script>

<?php endif; ?>