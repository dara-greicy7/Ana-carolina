<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'name' => 'widget/slider-chester',

    'main' => 'YOOtheme\\Widgetkit\\Widget\\Widget',

    'config' => array(

        'name'  => 'slider-chester',
        'label' => 'Slider Chester',
        'core'  => false,
        'icon'  => 'plugins/widgets/slider-chester/widget.svg',
        'view'  => 'plugins/widgets/slider-chester/views/widget.php',
        'item'  => array('title', 'content', 'media'),
        'settings' => array(
            'slidenav'                 => 'default',
            'slidenav_contrast'        => true,
            'autoplay'                 => false,
            'interval'                 => '3000',
            'autoplay_pause'           => true,
            'min_height'               => '300',

            'media'                    => true,
            'image_width'              => 'auto',
            'image_height'             => 'auto',
            'overlay_hover'            => true,
            'overlay_background'       => 'hover',
            'overlay_animation'        => 'fade',
            'overlay_image'            => 'static',
            'image_animation'          => 'fade',
            'overlay_link'             => false,

            'title'                    => true,
            'content'                  => true,
            'title_size'               => 'h3',
            'content_size'             => '',
            'text_align'               => 'center',
            'link'                     => true,
            'link_style'               => 'button',
            'link_text'                => 'Read more',

            'link_target'              => false,
            'class'                    => ''
        )

    ),

    'events' => array(

        'init.site' => function($event, $app) {

        },

        'init.admin' => function($event, $app) {
            $app['angular']->addTemplate('slider-chester.edit', 'plugins/widgets/slider-chester/views/edit.php', true);
        }

    )

);
