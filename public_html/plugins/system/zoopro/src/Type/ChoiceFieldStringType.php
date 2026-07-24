<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

class ChoiceFieldStringType
{
    /**
     * @return array
     */
    public static function config()
    {
        $field = [
            'type' => 'String',
            'args' => [
                'separator' => [
                    'type' => 'String',
                ],
            ],
            'metadata' => [
                'arguments' => [
                    'separator' => [
                        'label' => 'Separator',
                        'description' => 'Set the separator between fields.',
                        'default' => ', ',
                    ],
                ],
            ],
            'extensions' => [
                'call' => __CLASS__ . '::resolve',
            ],
        ];

        return [

            'fields' => [

                'name' => array_merge_recursive($field, [
                    'metadata' => [
                        'label' => 'Names',
                    ],
                ]),

                'value' => array_merge_recursive($field, [
                    'metadata' => [
                        'label' => 'Values',
                    ],
                ]),

            ],

        ];
    }

    public static function resolve($item, $args, $context, $info)
    {
        $args += ['separator' => ', '];

        return join($args['separator'], array_column($item, $info->fieldName));
    }
}
