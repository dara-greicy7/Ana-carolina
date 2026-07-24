<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

?>

<?php if ($this->config->get('repeatable')) : ?>

	<div id="<?php echo $this->identifier; ?>" class="repeat-elements filespro zl">
		<ul class="repeatable-list">

			<?php $this->rewind(); ?>
			<?php foreach($this as $self) : ?>
				<li class="repeatable-element">
					<?php echo $this->_renderSubmission($params); ?>
				</li>
			<?php endforeach; ?>

			<?php $this->rewind(); ?>

			<li class="repeatable-element hidden">
				<?php echo preg_replace('/(elements\[\S+])\[(\d+)\]/', '$1[-1]', $this->_renderSubmission($params)); ?>
			</li>

		</ul>
		<p class="add">
			<a class="zl-btn" href="javascript:void(0);"><?php echo JText::_('PLG_ZLFRAMEWORK_ADD_INSTANCE'); ?></a>
		</p>
	</div>

	<script type="text/javascript">
		jQuery('#<?php echo $this->identifier; ?>').ElementRepeatablePro({ msgDeleteElement : '<?php echo JText::_('PLG_ZLFRAMEWORK_DELETE_ELEMENT'); ?>', msgSortElement : '<?php echo JText::_('PLG_ZLFRAMEWORK_SORT_ELEMENT'); ?>', msgLimitReached : '<?php echo JText::_('PLG_ZLFRAMEWORK_INSTANCE_LIMIT_REACHED'); ?>', instanceLimit: '<?php echo $this->config->get('instancelimit') ?>' });
	</script>
	
<?php else : ?>

	<div id="<?php echo $this->identifier; ?>" class="repeatable-element filespro single zl">
		<?php echo $this->_renderSubmission($params); ?>
	</div>

<?php endif; ?>