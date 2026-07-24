<?php

return array(

    'name' => 'extension/pro',

    'autoload' => array(
        'YOOtheme\\Widgetkit\\Pro\\' => 'src'
    ),

    'events' => array(

        'init' => function() {
            if (class_exists(YOOtheme\Application::class, false)) {
                $app = YOOtheme\Application::getInstance();
                $app->load(__DIR__ . '/bootstrap.php');
            }
        }

    )

);
