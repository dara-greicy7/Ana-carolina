<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

switch ($settings['position']) {
    case 'top':
        $nav = '';
        break;
    case 'left':
        $nav = 'tm-switcher-nav-left uk-flex-column';
        break;
    case 'right':
        $nav = 'tm-switcher-nav-right uk-flex-column';
        break;
    case 'bottom':
        $nav = 'tm-switcher-nav-bottom';
        break;
}

// Animation
$animation = ($settings['animation'] != 'none') ? ',animation:\'' . $settings['animation'] . '\'' : '';

?>

<ul class="tm-switcher-nav uk-flex uk-flex-center <?php echo $nav; ?>" data-uk-switcher="{connect:'#wk-<?php echo $settings['id']; ?>'<?php echo $animation; ?>}">
<?php foreach ($items as $item) : ?>
    <?php

    // Icon
    $icon = '';
    $attrs['width']  = $item['nav_icon.width'];
    $attrs['height'] = $item['nav_icon.height'];

    if ($item->type('nav_icon') == 'image') {
        $attrs['alt'] = strip_tags($item['title']);

        $icon = $item->media('nav_icon', $attrs);
    }

    ?>

    <li class="uk-flex uk-flex-middle uk-flex-center uk-flex-column">
        <a class="uk-position-cover uk-link-reset" href=""></a>
        <?php if ($settings['nav'] == 'icon' || $settings['nav'] == 'both') echo $icon; ?>
        <?php if ($settings['nav'] == 'title' || $settings['nav'] == 'both') : ?>
            <span><?php echo $item['title']; ?></span>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
