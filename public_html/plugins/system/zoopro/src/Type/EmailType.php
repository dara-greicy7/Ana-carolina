<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

class EmailType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'mailto' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Src',
                    ],
                ],

                'text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link Text',
                    ],
                ],

            ],

        ];
    }
}
