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

<?php if ($this->checkPosition('media')) : ?>
<div class="sub-pos-media">
	<?php echo $this->renderPosition('media'); ?>
</div>
<?php endif; ?>

<?php if ($this->checkPosition('title')) : ?>
<h4 class="sub-pos-title">
	<?php echo $this->renderPosition('title'); ?>
</h4>
<?php endif; ?>

<?php if ($this->checkPosition('subtitle')) : ?>
<p class="sub-pos-subtitle">
	<?php echo $this->renderPosition('subtitle', array('style' => 'comma')); ?>
</p>
<?php endif; ?>

<?php if ($this->checkPosition('links')) : ?>
<p class="sub-pos-links">
	<?php echo $this->renderPosition('links', array('style' => 'pipe')); ?>
</p>
<?php endif;