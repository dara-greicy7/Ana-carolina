<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use YOOtheme\Str;

class ItemQueryType
{
    /**
     * @param string $name
     * @param \Type  $type
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($name, \Type $type, $applications)
    {
        return [
            'fields' => [

                Str::camelCase($type->id) => [
                    'type' => $name,
                    'metadata' => [
                        'label' => $type->getName(),
                        'view' => array_map(function ($application) use ($type) {
                            return "com_zoo.{$application->id}.{$type->id}";
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
        if (isset($root['item'])) {
            return $root['item'];
        }
    }
}
