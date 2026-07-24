<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<li class="uk-width-1-2@m">
	<?php if ($this->checkPosition('media')) : ?>
	<div class="uk-align-left@m">
		<?php echo $this->renderPosition('media'); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->checkPosition('title')) : ?>
	<h4 class="uk-margin-remove">
		<?php echo $this->renderPosition('title'); ?>
	</h4>
	<?php endif; ?>

	<?php if ($this->checkPosition('description')) : ?>
		<?php echo $this->renderPosition('description'); ?>
	<?php endif; ?>

	<?php if ($this->checkPosition('specification')) : ?>
	<ul class="uk-list">
		<?php echo $this->renderPosition('specification', array('style' => 'uikit_list')); ?>
	</ul>
	<?php endif; ?>

	<?php if ($this->checkPosition('links')) : ?>
	<ul class="uk-subnav uk-subnav-divider">
		<?php echo $this->renderPosition('links', array('style' => 'uikit_subnav')); ?>
	</ul>
	<?php endif; ?>
</li>