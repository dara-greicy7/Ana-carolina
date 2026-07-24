<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

class TagQueryType
{
    /**
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($applications)
    {
        return [
            'fields' => [

                'zooTag' => [
                    'type' => 'ZooTag',
                    'metadata' => [
                        'label' => 'Tag',
                        'view' => array_map(function ($application) {
                            return "com_zoo.{$application->id}.tag";
                        }, $applications),
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['tag'], $root['application'])) {
            return (object) [
                'name' => $root['tag'],
                'application_id' => $root['application']->id,
            ];
        }
    }
}
