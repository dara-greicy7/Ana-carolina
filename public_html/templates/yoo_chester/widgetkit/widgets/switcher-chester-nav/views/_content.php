<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// Margin
$margin = '';
if ($settings['position'] != 'left' && $settings['position'] != 'right') {
    $margin = 'uk-margin-' . $settings['position'];
}

// Panel
$panel = $settings['panel'] ? 'uk-panel uk-panel-space' : 'uk-panel';

// Title Size
switch ($settings['title_size']) {
    case 'panel':
        $title_size = 'uk-panel-title';
        break;
    case 'large':
        $title_size = 'uk-heading-large uk-margin-top-remove';
        break;
    default:
        $title_size = 'uk-' . $settings['title_size'] . ' uk-margin-top-remove';
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

// Media Border
$border = ($settings['media_border'] != 'none') ? 'uk-border-' . $settings['media_border'] : '';

// Link Target
$link_target = ($settings['link_target']) ? ' target="_blank"' : '';

// Content Width
$content_width = 'uk-width-'.$settings['media_breakpoint'].'-'.$settings['content_width'];

?>

<ul id="wk-<?php echo $settings['id']; ?>" class="uk-switcher tm-switcher-chester-nav uk-text-<?php echo $settings['text_align']; ?> <?php echo $margin; ?>" data-uk-check-display>
<?php foreach ($items as $item) : ?>

    <?php

        // Social Buttons
        $socials = '';
        if ($settings['social_buttons']) {
            $socials .= $item['twitter'] ? '<div><a class="uk-icon-button uk-icon-twitter" href="'. $item->escape('twitter') .'"></a></div>': '';
            $socials .= $item['facebook'] ? '<div><a class="uk-icon-button uk-icon-facebook" href="'. $item->escape('facebook') .'"></a></div>': '';
            $socials .= $item['google-plus'] ? '<div><a class="uk-icon-button uk-icon-google-plus" href="'. $item->escape('google-plus') .'"></a></div>': '';
            $socials .= $item['email'] ? '<div><a class="uk-icon-button uk-icon-envelope-o" href="mailto:'. $item->escape('email') .'"></a></div>': '';
        }

        // Second Image as Overlay
        $media2 = '';
        if ($settings['media_overlay'] == 'image') {
            foreach ($item as $field) {
                if ($field != 'media' && $field != 'nav_icon' && $item->type($field) == 'image') {
                    $media2 = $field;
                    break;
                }
            }
        }

        // Media Type
        $attrs  = array('class' => '');
        $width  = $item['media.width'];
        $height = $item['media.height'];

        if ($item->type('media') == 'image') {
            $attrs['alt'] = strip_tags($item['title']);

            $attrs['class'] .= ($border) ? $border : '';
            $attrs['class'] .= ($settings['media_animation'] != 'none' && !$media2) ? ' uk-overlay-' . $settings['media_animation'] : '';

            $width  = ($settings['image_width'] != 'auto') ? $settings['image_width'] : '';
            $height = ($settings['image_height'] != 'auto') ? $settings['image_height'] : '';
        }

        if ($item->type('media') == 'video') {
            $attrs['class'] = 'uk-responsive-width';
            $attrs['controls'] = true;
        }

        if ($item->type('media') == 'iframe') {
            $attrs['class'] = 'uk-responsive-width';
        }

        $attrs['width']  = ($width) ? $width : '';
        $attrs['height'] = ($height) ? $height : '';

        if (($item->type('media') == 'image') && ($settings['image_width'] != 'auto' || $settings['image_height'] != 'auto')) {
            $media = $item->thumbnail('media', $width, $height, $attrs);
        } else {
            $media = $item->media('media', $attrs);
        }

        // Second Image as Overlay
        if ($media2) {

            $attrs['class'] .= ' uk-overlay-panel uk-overlay-image';
            $attrs['class'] .= ($settings['media_animation'] != 'none') ? ' uk-overlay-' . $settings['media_animation'] : '';

            $media2 = $item->thumbnail($media2, $width, $height, $attrs);
        }

        // Link and Overlay
        if ($item['link'] && ($settings['media_overlay'] == 'link' || $settings['media_overlay'] == 'icon' || $settings['media_overlay'] == 'image')) {

            $media = '<div class="uk-overlay uk-overlay-hover ' . $border . '">' . $media;

            if ($media2) {
                $media .= $media2;
            }

            if ($settings['media_overlay'] == 'icon') {
                $media .= '<div class="uk-overlay-panel uk-overlay-background uk-overlay-icon uk-overlay-' . $settings['overlay_animation'] . '"></div>';
            }

            $media .= '<a class="uk-position-cover" href="' . $item->escape('link') . '"' . $link_target . '></a>';
            $media .= '</div>';
        }

        if ($socials && $settings['media_overlay'] == 'social-buttons') {
            $media  = '<div class="uk-overlay uk-overlay-hover ' . $border . '">' . $media;
            $media .= '<div class="uk-overlay-panel uk-overlay-background uk-overlay-' . $settings['overlay_animation'] . ' uk-flex uk-flex-center uk-flex-middle uk-text-center"><div>';
            $media .= '<div class="uk-grid uk-grid-small" data-uk-grid-margin>' . $socials . '</div>';
            $media .= '</div></div>';
            $media .= '</div>';
        }

    ?>

    <li>
        <div class="<?php echo $panel; ?>">

            <?php if ($item['media'] && $settings['media']) : ?>
            <div class="uk-margin-large-bottom uk-text-center uk-position-relative">
                <?php echo $media; ?>

                <?php if ($item['title'] && $settings['title'] && $settings['title_overlay']) : ?>
                <h3 class="<?php echo $title_size ?> tm-title-overlay"><?php echo $item['title']; ?></h3>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="uk-panel <?php echo $content_width ?> uk-align-center">

                <?php if ($item['title'] && $settings['title'] && !$settings['title_overlay']) : ?>
                <h3 class="<?php echo $title_size; ?>"><?php echo $item['title']; ?></h3>
                <?php endif; ?>

                <?php if ($item['content'] && $settings['content']) : ?>
                <div class="uk-margin"><?php echo $item['content']; ?></div>
                <?php endif; ?>

                <?php if ($socials && ($settings['media_overlay'] != 'social-buttons')) : ?>
                <div class="uk-grid uk-grid-small uk-flex-<?php echo $settings['text_align']; ?>" data-uk-grid-margin><?php echo $socials; ?></div>
                <?php endif; ?>

                <?php if ($item['link'] && $settings['link']) : ?>
                <p><a<?php if($link_style) echo ' class="' . $link_style . '"'; ?> href="<?php echo $item->escape('link'); ?>"<?php echo $link_target; ?>><?php echo $app['translator']->trans($settings['link_text']); ?></a></p>
                <?php endif; ?>

            </div>
        </div>
    </li>

<?php endforeach; ?>
</ul>
