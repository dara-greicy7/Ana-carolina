<?php

namespace YOOtheme\Widgetkit\Pro;

use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Widgetkit\Application;

class SourceListener
{
    public static function initCustomizer(Metadata $metadata)
    {
        $app = Application::getInstance();

        $config = array(
            'url' => $app['url']->base(),
            'route' => $app['url']->route()
        );

        if (isset($app['csrf'])) {
            $config['csrf'] = $app['csrf']->generate();
        }

        $metadata->set('script:widgetkit.config', sprintf('var widgetkit = %s;', json_encode(array('config' => $config))));
        $metadata->set('script:customizer.widgetkit', ['src' => Path::get('../pro.js'),  'defer' => true]);
    }
}
