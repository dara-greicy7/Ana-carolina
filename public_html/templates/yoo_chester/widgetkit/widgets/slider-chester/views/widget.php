<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// JS Options
$options = array();
$options[] = 'center: true';
$options[] = ($settings['autoplay']) ? 'autoplay: true ' : '';
$options[] = ($settings['interval'] != '3000') ? 'autoplayInterval: ' . $settings['interval'] : '';
$options[] = ($settings['autoplay_pause']) ? '' : 'pauseOnHover: false';

$options = '{'.implode(',', array_filter($options)).'}';

// Title Size
switch ($settings['title_size']) {
    case 'large':
        $title_size = 'uk-heading-large uk-margin-top-remove';
        break;
    default:
        $title_size = 'uk-' . $settings['title_size'] . ' uk-margin-top-remove';
}

// Content Size
switch ($settings['content_size']) {
    case 'large':
        $content_size = 'uk-text-large';
        break;
    case 'h2':
    case 'h3':
    case 'h4':
        $content_size = 'uk-' . $settings['content_size'];
        break;
    default:
        $content_size = '';
}

// Link Style
switch ($settings['link_style']) {
    case 'button':
        $link_style = 'uk-button';
        break;
    case 'primary':
        $link_style = 'uk-button uk-button-primary';
        break;
    case 'button-large':
        $link_style = 'uk-button uk-button-large';
        break;
    case 'primary-large':
        $link_style = 'uk-button uk-button-large uk-button-primary';
        break;
    case 'button-link':
        $link_style = 'uk-button uk-button-link';
        break;
    default:
        $link_style = '';
}

// Link Target
$link_target = ($settings['link_target']) ? ' target="_blank"' : '';

// Position Relative
if ($settings['slidenav'] == 'default') {
    $position_relative = 'uk-slidenav-position';
} else {
    $position_relative = 'uk-position-relative';
}

// Overlays
$overlay_hover = isset($settings['overlay_hover']) && $settings['overlay_hover'] ? 'uk-overlay-' . $settings['overlay_animation'] : 'uk-ignore';
$background = isset($settings['overlay_background']) && ($settings['overlay_background'] == 'hover') ? 'uk-overlay-' . $settings['overlay_animation'] : 'uk-ignore';

if (count($items) && count($items) < 5) {

    // copy items if less than 5 to correctly calculate slider
    // TODO: fix in UIkit

    $items = $items->getArrayCopy();

    while(count($items) < 5) {
        $items = array_merge($items,$items);
    }
}

?>

<div class="tm-slider-chester <?php echo $position_relative; ?> <?php echo $settings['class']; ?>" data-uk-slider="<?php echo $options; ?>">

    <div class="uk-slider-container">
        <ul class="uk-slider">
        <?php foreach ($items as $item) :

                // Media Type
                $width = $item['media.width'];
                $height = $item['media.height'];

                if ($item->type('media') == 'image' && $settings['media']) {
                    if ($settings['image_width'] != 'auto' || $settings['image_height'] != 'auto') {

                        $width  = $settings['image_width'];
                        $height = $settings['image_height'];

                        $media = $item->thumbnail('media', $width, $height, array('class' => 'uk-invisible'));
                        $media_background = 'background-image: url(' . $item->thumbnail('media', $width, $height, array(), true) . ');';

                    } else {
                        $attrs['width']  = ($width) ? $width : '';
                        $attrs['height'] = ($height) ? $height : '';
                        $attrs['class']  = 'uk-invisible';

                        $media = $item->media('media', $attrs);;
                        $media_background = 'background-image: url(' . $item['media'] . ');';
                    }
                } else {
                    $media = '';
                    $media_background = '';
                }

                $min_height = (isset($settings['min_height'])) ? 'min-height: ' . $settings['min_height'] . 'px;' : '';

                if ($settings['overlay_image'] != 'hover') {
                    $media_background = 'style="' . $min_height . $media_background . '"';
                }


                // Second Image as Overlay
                $media2 = '';
                if ($settings['overlay_image'] == 'second') {
                    foreach ($item as $field) {
                        if ($field != 'media' && $item->type($field) == 'image') {
                            $media2 = 'style="background-image: url(' . $item->thumbnail($field, $width, $height, array(), true) . ');"';
                            break;
                        }
                    }
                }

            ?>

            <li>

                <div class="uk-overlay uk-overlay-hover uk-cover-background" <?php echo $media_background; ?>>

                    <?php if ($media2) : ?>
                    <div class="uk-overlay-panel uk-cover-background <?php if ($settings['image_animation'] != 'none') echo 'uk-overlay-' . $settings['image_animation']; ?>" <?php echo $media2; ?>></div>
                    <?php endif; ?>

                    <?php if ($settings['overlay_background'] != 'none') : ?>
                    <div class="uk-overlay-panel uk-overlay-background <?php echo $background; ?>"></div>
                    <?php endif; ?>

                    <div class="uk-overlay-panel <?php echo $overlay_hover; ?> uk-flex uk-flex-center uk-flex-middle uk-text-<?php echo $settings['text_align']; ?>">
                        <div>

                            <?php if ($item['title'] && $settings['title']) : ?>
                            <h3 class="<?php echo $title_size; ?> uk-margin-top-remove">

                                <?php if ($item['link']) : ?>
                                    <a class="uk-link-reset" href="<?php echo $item->escape('link') ?>"<?php echo $link_target; ?>><?php echo $item['title']; ?></a>
                                <?php else : ?>
                                    <?php echo $item['title']; ?>
                                <?php endif; ?>

                            </h3>
                            <?php endif; ?>

                            <?php if ($item['content'] && $settings['content']) : ?>
                            <div class="<?php echo $content_size; ?> uk-margin"><?php echo $item['content']; ?></div>
                            <?php endif; ?>

                            <?php if ($item['link'] && $settings['link']) : ?>
                            <p><a<?php if($link_style) echo ' class="' . $link_style . '"'; ?> href="<?php echo $item->escape('link'); ?>"<?php echo $link_target; ?>><?php echo $app['translator']->trans($settings['link_text']); ?></a></p>
                            <?php endif; ?>

                        </div>
                    </div>

                    <?php if ($item['link'] && $settings['overlay_link']) : ?>
                    <a class="uk-position-cover" href="<?php echo $item->escape('link'); ?>"<?php echo $link_target; ?>></a>
                    <?php endif; ?>

                    <canvas class="uk-responsive-width" height="<?php echo $height; ?>" width="<?php echo $width; ?>"></canvas>

                </div>

            </li>

        <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($settings['slidenav'] == 'default') : ?>
    <a href="#" class="uk-slidenav <?php if ($settings['slidenav_contrast']) echo 'uk-slidenav-contrast'; ?> uk-slidenav-previous uk-hidden-touch" data-uk-slider-item="previous"></a>
    <a href="#" class="uk-slidenav <?php if ($settings['slidenav_contrast']) echo 'uk-slidenav-contrast'; ?> uk-slidenav-next uk-hidden-touch" data-uk-slider-item="next"></a>
    <?php endif ?>

</div>
