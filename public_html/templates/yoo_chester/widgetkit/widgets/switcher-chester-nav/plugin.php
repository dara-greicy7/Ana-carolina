<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'name' => 'widget/switcher-chester-nav',

    'main' => 'YOOtheme\\Widgetkit\\Widget\\Widget',

    'config' => array(

        'name'   => 'switcher-chester-nav',
        'label'  => 'Switcher Chester Nav',
        'core'   => false,
        'icon'   => 'plugins/widgets/switcher-chester-nav/widget.svg',
        'view'   => 'plugins/widgets/switcher-chester-nav/views/widget.php',
        'fields' => array(
            array(
                'type'  => 'media',
                'name'  => 'nav_icon',
                'label' => 'Nav Icon'
            )
        ),
        'item'   => array('title', 'content', 'media'),
        'settings' => array(
            'nav'               => 'both',
            'position'          => 'top',
            'panel'             => false,
            'animation'         => 'none',

            'media'             => true,
            'image_width'       => 'auto',
            'image_height'      => 'auto',
            'media_border'      => 'none',
            'media_overlay'     => 'icon',
            'overlay_animation' => 'fade',
            'media_animation'   => 'scale',

            'title'             => true,
            'content'           => true,
            'social_buttons'    => true,
            'title_size'        => 'panel',
            'title_overlay'     => true,
            'content_width'     => '1-1',
            'media_breakpoint'  => 'medium',
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
            $app['angular']->addTemplate('switcher-chester-nav.edit', 'plugins/widgets/switcher-chester-nav/views/edit.php', true);
        }

    )

);
