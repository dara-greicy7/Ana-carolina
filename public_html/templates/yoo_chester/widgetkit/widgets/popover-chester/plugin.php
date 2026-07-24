<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'name' => 'widget/popover-chester',

    'main' => 'YOOtheme\\Widgetkit\\Widget\\Widget',

    'config' => array(

        'name'  => 'popover-chester',
        'label' => 'Popover Chester',
        'core'  => false,
        'icon'  => 'plugins/widgets/popover-chester/widget.svg',
        'view'  => 'plugins/widgets/popover-chester/views/widget.php',
        'item'  => array('title', 'content', 'media'),
        'fields' => array(
            array(
                'type' => 'text',
                'name' => 'left-top',
                'label' => 'Left Top'
            ),
            array(
                'type' => 'text',
                'name' => 'left',
                'label' => 'Left'
            )
        ),
        'settings' => array(
            'width'             => '',
            'image'             => '',
            'image_hero_width'  => 'auto',
            'image_hero_height' => 'auto',
            'position'          => 'left-top',
            'mode'              => 'hover',
            'toggle'            => 'plus',
            'contrast'          => true,
            'panel'             => 'box',
            'panel_link'        => false,

            'media'             => true,
            'image_width'       => 'auto',
            'image_height'      => 'auto',
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
            $app['angular']->addTemplate('popover-chester.edit', 'plugins/widgets/popover-chester/views/edit.php', true);
        }

    )

);
