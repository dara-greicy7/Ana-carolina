<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

class CategoryQueryType
{
    /**
     * @param string       $type
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($type, $applications)
    {
        return [
            'fields' => [
                'category' => [
                    'type' => $type,
                    'metadata' => [
                        'label' => 'Category',
                        'view' => array_map(function ($application) {
                            return "com_zoo.{$application->id}.category";
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
        if (isset($root['category'])) {
            return $root['category'];
        }
    }
}
