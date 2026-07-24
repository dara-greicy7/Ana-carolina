<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

$custom_options 	= $this->config->find('specific._custom_options', 0);
$custom_title 		= $this->config->find('specific._custom_title', 0);
$custom_link 		= $this->config->find('specific._custom_link', 0);
$custom_lightbox 	= $this->config->find('specific._custom_lightbox', 0);
// 'specific._custom_spotlight' is DEPRECATED
$custom_overlay 	= $this->config->find('specific._custom_spotlight', 0) || $this->config->find('specific._custom_overlay', 0);

?>

<div class="repeatable-content">

	<div class="row">
		<?php echo $this->app->html->_('control.text', $this->getControlName('file'), $this->get('file'), 'class="image-element filespro file" title="'.JText::_('PLG_ZLFRAMEWORK_IMAGE').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_IMAGE').'"'); ?>
		<?php echo $this->getFileDetailsDom() ?>
    </div>

    <?php if ($custom_options) : ?>
	<div class="more-options">
		<div class="trigger">
			<div>
				<?php if ($custom_overlay) : ?><div class="spotlight button"><?php echo JText::_('PLG_ZLELEMENTS_IMGP_OVERLAY'); ?></div><?php endif; ?>
				<?php if ($custom_lightbox) : ?><div class="lightbox button"><?php echo JText::_('PLG_ZLFRAMEWORK_LIGHTBOX'); ?></div><?php endif; ?>
				<?php if ($custom_link) : ?><div class="link button"><?php echo JText::_('PLG_ZLFRAMEWORK_LINK'); ?></div><?php endif; ?>
				<?php if ($custom_title) : ?><div class="title button"><?php echo JText::_('PLG_ZLFRAMEWORK_TITLE'); ?></div><?php endif; ?>
				
			</div>
		</div>

		<?php if ($custom_title) : ?>	
		<div class="title options">

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('title'), $this->get('title'), 'maxlength="255" title="'.JText::_('PLG_ZLFRAMEWORK_TITLE').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_TITLE').'"'); ?>
			</div>

		</div>
		<?php endif; ?>
		
		<?php if ($custom_link) : ?>
		<div class="link options">

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('link'), $this->get('link'), 'size="60" maxlength="255" title="'.JText::_('PLG_ZLFRAMEWORK_LINK').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_LINK').'"'); ?>
			</div>

			<div class="row">
				<strong><?php echo JText::_('PLG_ZLFRAMEWORK_NEW_WINDOW'); ?></strong>
				<?php echo $this->app->html->_('select.booleanlist', $this->getControlName('target'), $this->get('target'), $this->get('target')); ?>
			</div>

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('rel'), $this->get('rel'), 'size="60" maxlength="255" title="'.JText::_('PLG_ZLFRAMEWORK_REL').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_REL').'"'); ?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php if ($custom_lightbox) : ?>		
		<div class="lightbox options">
			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('file2'), $this->get('file2'), 'class="image-subelement filespro file" style="width:345px;" title="'.JText::_('PLG_ZLELEMENTS_IMGP_LIGHTBOX_IMAGE').'"'); ?>
				<?php echo $this->getFileDetailsDom($this->get('file2')) ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($custom_overlay) : ?>
		<div class="spotlight options">
			<div class="row">
				<?php echo $this->app->html->_('control.arraylist', array(
					'' => 'None',
					'default' => 'Default',
					'top' => 'Top',
					'bottom' => 'Bottom',
					'left' => 'Left',
					'right' => 'Right',
					'fade' => 'Fade'
				), array(), $this->getControlName('overlay_effect'), null, 'value', 'text', $this->get('spotlight_effect', '') ?: $this->get('overlay_effect', '')); ?>
			</div>

			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('caption'), $this->get('caption'), 'size="60" style="width:200px;margin-right:5px;" title="'.JText::_('PLG_ZLFRAMEWORK_CAPTION').'" placeholder="'.JText::_('PLG_ZLFRAMEWORK_CAPTION').'"'); ?>
			</div>
		</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>
	
</div>