<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'name' => 'widget/switcher-chester',

    'main' => 'YOOtheme\\Widgetkit\\Widget\\Widget',

    'config' => array(

        'name'  => 'switcher-chester',
        'label' => 'Switcher Chester',
        'core'  => false,
        'icon'  => 'plugins/widgets/switcher-chester/widget.svg',
        'view'  => 'plugins/widgets/switcher-chester/views/widget.php',
        'item'  => array('title', 'content', 'media'),
        'settings' => array(
            'nav'               => 'thumbnails',
            'thumbnail_width'   => 'auto',
            'thumbnail_height'  => 'auto',
            'thumbnail_alt'     => false,
            'thumbnail_title'   => true,
            'position'          => 'right',
            'alignment'         => 'left',
            'width'             => '1-4',
            'panel'             => false,
            'animation'         => 'none',

            'media'             => true,
            'image_width'       => 'auto',
            'image_height'      => 'auto',
            'media_align'       => 'top',
            'media_width'       => '1-2',
            'media_breakpoint'  => 'medium',
            'content_width'     => '1-1',
            'content_align'     => true,
            'media_border'      => 'none',
            'media_overlay'     => 'icon',
            'overlay_animation' => 'fade',
            'media_animation'   => 'scale',

            'title'             => true,
            'content'           => true,
            'social_buttons'    => true,
            'title_size'        => 'panel',
            'text_align'        => 'left',
            'link'              => true,
            'link_style'        => 'button',
            'link_text'         => 'Read more',

            'link_target'       => false,
            'class'             => ''
        )

    ),

    'events' => array(

        'init.site' => function($event, $app) {
        },

        'init.admin' => function($event, $app) {
            $app['angular']->addTemplate('switcher-chester.edit', 'plugins/widgets/switcher-chester/views/edit.php', true);
        }

    )

);
