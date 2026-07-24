<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use function YOOtheme\app;
use YOOtheme\Zoo;

class CustomCategoryQueryType
{
    /**
     * @param string         $type
     * @param \Application   $application
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($type, $application, $applications)
    {
        $appOptions = array_column($applications, 'id', 'name');

        return [
            'fields' => [

                'customCategory' => [
                    'type' => $type,
                    'args' => [
                        'appid' => [
                            'type' => 'String',
                        ],
                        'id' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => "Custom ZOO {$application->getMetaData('name')}  Category",
                        'group' => 'ZOO',
                        'fields' => [
                            'appid' => [
                                'label' => 'Application',
                                'description' => 'Only categories from the selected application are loaded.',
                                'type' => 'select',
                                'options' => $appOptions,
								'defaultIndex' => 0,
                                'attrs' => [
                                    'class' => 'uk-height-small',
                                ],
                                'show' => count($appOptions) > 1,
                            ],
                            'id' => [
                                'label' => 'Category',
								'type' => 'select',
								'defaultIndex' => 0,
								'options' => [
									['evaluate' => 'config.zoo[appid]["categories"]'],
								],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],

        ];
    }

    public static function resolve($root, array $args)
    {
        /**
         * @var Zoo $zoo
         */
        $zoo = app(Zoo::class);

        if (!isset($args['id'])) {
            return;
        }

        return $zoo->table->category->get($args['id']);
    }
}
