<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

?>

<div class="repeatable-content">

	<div class="row">
		<input type="text" name="<?php echo $this->getControlName('file'); ?>" class="mediapro-element file" value="<?php echo $this->get('file'); ?>" placeholder="<?php echo JText::_('PLG_ZLFRAMEWORK_PATH') ?>"/>
		<?php echo $this->getFileDetailsDom() ?>
	</div>

	<div class="more-options">
		<div class="trigger">
			<div>
				<div class="advanced button hide"><?php echo JText::_('PLG_ZLFRAMEWORK_HIDE_OPTIONS'); ?></div>
				<div class="advanced button"><?php echo JText::_('PLG_ZLFRAMEWORK_SHOW_OPTIONS'); ?></div>
			</div>
		</div>

		<div class="advanced options">
			<div class="row short">
				<?php echo $this->app->html->_('control.text', $this->getControlName('width'), $this->get('width', $this->config->get('defaultwidth')), 'maxlength="4" title="'.JText::_('PLG_ZLFRAMEWORK_WIDTH').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_WIDTH').'"'); ?>
			</div>

			<div class="row short">
				<?php echo $this->app->html->_('control.text', $this->getControlName('height'), $this->get('height', $this->config->get('defaultheight')), 'maxlength="4" title="'.JText::_('PLG_ZLFRAMEWORK_HEIGHT').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_HEIGHT').'"'); ?>
			</div>

			<div class="row">
				<strong><?php echo JText::_('PLG_ZLELEMENTS_MP_AUTOPLAY'); ?></strong>
				<?php echo $this->app->html->_('select.booleanlist', $this->getControlName('autoplay'), '', $this->get('autoplay', $this->config->get('defaultautoplay', false))); ?>
			</div>

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('image'), $this->get('image', $this->config->get('defaultwidth')), 'title="'.JText::_('PLG_ZLFRAMEWORK_IMAGE').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_IMAGE').'"'); ?>
			</div>

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('title'), $this->get('title'), 'size="60" maxlength="255" placeholder="'.JText::_('PLG_ZLELEMENTS_MP_MEDIA_TITLE').'"'); ?>
			</div>
		</div>
	</div>

</div>