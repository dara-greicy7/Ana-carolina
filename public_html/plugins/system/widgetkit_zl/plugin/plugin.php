<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

$config = array(

    'name'     => 'content/zoopro',

    'main'     => 'YOOtheme\\Widgetkit\\Joomla\\Zoopro\\Type',

    'autoload' => array(

        'YOOtheme\\Widgetkit\\Joomla\\Zoopro\\' => 'src'

    ),

    'config' => array(

        'name'  => 'zoopro',
        'label' => 'ZOO Pro',
        'icon'  => 'plugins/content/zoopro/content.svg',
        'item'  => array('title', 'content', 'media', 'location'),
        'data'  => array(
            'application'    => 0,
            'mode'           => 'categories',
            'type'           => '',
            'category'       => '',
            'subcategories'  => 0,
            'order'          => array(
                '_reversed'     => false,
                '_random'       => false,
                '_alphanumeric' => false
            ),
            'count'          => 4,
            'mapping_layout' => 'mapping'
        )

    ),

    'events'   => array(

        'init.admin' => function ($event, $app) {
            $app['scripts']->add('zoopro-controller', 'plugins/content/zoopro/assets/controller.js');
            $app['scripts']->add('zoopro-picker', 'plugins/content/zoopro/assets/picker.js');
            $app['scripts']->combine('zoopro', 'zoopro-*');

            $app['angular']->addTemplate('zoopro.edit', 'plugins/content/zoopro/views/edit.php');
            $app['angular']->addTemplate('zoopro.picker', 'plugins/content/zoopro/views/picker.php');
        },

        'init.site'  => function ($event, $app) {
            $app['events']->subscribe(new YOOtheme\Widgetkit\Joomla\Zoopro\Transformer);
        }

    )

);

return defined('_JEXEC') ? $config : false;
