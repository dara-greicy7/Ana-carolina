<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$info[] = JText::_('PLG_ZLELEMENTS_DWP_HITS').': '.(int)$this->get('hits', 0);
$info   = ' ('.implode(', ', $info).')';
$hits 	= $this->get('hits', 0);

?>

<div class="repeatable-content">

    <div class="row">
		<input type="text" name="<?php echo $this->getControlName('file'); ?>" class="download-element filespro file" value="<?php echo $this->get('file'); ?>" placeholder="<?php echo JText::_('PLG_ZLFRAMEWORK_FILE'); ?>"/>
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
		
			<div class="row">
				<?php echo $this->app->html->_('control.text', $this->getControlName('title'), $this->_data->get('title'), 'size="60" maxlength="255" placeholder="'.JText::_('PLG_ZLFRAMEWORK_TITLE').'"'); ?>
			</div>
		
			<div class="row short download-limit">
				<?php echo $this->app->html->_('control.text', $this->getControlName('download_limit'), $this->get('download_limit'), 'size="6" maxlength="255" title="'.JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT').'" placeholder="'.JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT').'"'); ?>
			</div>
			
			<div class="reset-container">
				<?php if ($hits) : ?>
					<input name="reset-hits" type="button" class="button" value="<?php echo JText::_('PLG_ZLFRAMEWORK_RESET'); ?>"/>
				<?php endif; ?>
				<?php echo JText::_('PLG_ZLFRAMEWORK_HITS').': '.(int)$this->get('hits', 0); ?>: 
				<input name="<?php echo $this->getControlName('hits'); ?>" type="hidden" value="<?php echo $hits; ?>"/>
			</div>
		
		</div>
	</div>
	
</div>