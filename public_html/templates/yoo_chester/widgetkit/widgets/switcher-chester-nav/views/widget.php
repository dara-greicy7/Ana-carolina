<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// Id
$settings['id'] = substr(uniqid(), -3);

?>

<?php if ($settings['position'] == 'top' || $settings['position'] == 'bottom') : ?>

<div<?php if ($settings['class']) echo ' class="' . $settings['class'] . '"'; ?>>

    <?php if ($settings['position'] == 'top') : ?>
    <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name') . '/views/_nav.php', compact('items', 'settings')); ?>
    <?php endif ?>

    <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name')  . '/views/_content.php', compact('items', 'settings')); ?>

    <?php if ($settings['position'] == 'bottom') : ?>
    <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name')  . '/views/_nav.php', compact('items', 'settings')); ?>
    <?php endif ?>

</div>

<?php else : ?>

<div class="uk-grid uk-grid-match <?php echo $settings['class']; ?>" data-uk-grid-match="{target:'> div > ul'}" data-uk-grid-margin>
    <div class="uk-width-1-6<?php if ($settings['position'] == 'right') echo ' uk-float-right uk-flex-order-last-medium' ?>">
        <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name')  . '/views/_nav.php', compact('items', 'settings')); ?>
    </div>
    <div class="uk-width-5-6">
        <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name')  . '/views/_content.php', compact('items', 'settings')); ?>
    </div>
</div>

<?php endif ?>
