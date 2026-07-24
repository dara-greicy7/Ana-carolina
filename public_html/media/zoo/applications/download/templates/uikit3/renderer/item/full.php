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

<?php if ($this->checkPosition('top')) : ?>
	<?php echo $this->renderPosition('top', array('style' => 'uikit_block')); ?>
<?php endif; ?>
<?php if ($this->checkPosition('title')) : ?>
<h1 class="uk-h1"><?php echo $this->renderPosition('title'); ?></h1>
<?php endif; ?>

<div class="uk-grid" uk-grid uk-match-height>

    <div class="uk-width-3-4@m">
        <div class="uk-panel">
            <?php if ($this->checkPosition('specification')) : ?>
            <ul class="uk-list uk-list-divider">
                <?php echo $this->renderPosition('specification', array('style' => 'uikit_list')); ?>
            </ul>
            <?php endif; ?>

            <?php if ($this->checkPosition('button')) : ?>
            <div class="uk-margin">
                <?php echo $this->renderPosition('button'); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="uk-width-1-4@m">

        <div class="uk-card uk-card-default uk-card-body">
            <?php if ($this->checkPosition('media')) : ?>
            <div class="uk-margin">
                <?php echo $this->renderPosition('media'); ?>
            </div>
            <?php endif; ?>

            <?php if ($this->checkPosition('right')) : ?>
            <div class="uk-margin">
                <?php echo $this->renderPosition('right'); ?>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php if ($this->checkPosition('bottom')) : ?>
	<?php echo $this->renderPosition('bottom', array('style' => 'uikit_block')); ?>
<?php endif;
