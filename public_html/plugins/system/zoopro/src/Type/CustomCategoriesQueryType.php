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

class CustomCategoriesQueryType
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

                'customCategories' => [
                    'type' => [
                        'listOf' => $type,
                    ],
                    'args' => [
                        'appid' => [
                            'type' => 'String',
                        ],
                        'catid' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => "Custom ZOO {$application->getMetaData('name')} Categories",
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
                            'catid' => [
                                'label' => 'Parent Category',
                                'description' => 'Only categories from the selected parent category are loaded.',
								'type' => 'select',
                                'default' => '0',
								'options' => [
									['value' => '0', 'text' => 'Root'],
									['evaluate' => "config.zoo[appid]['categories']"],
								],
                            ],
                            '_offset' => [
                                'description' => 'Set the starting point and limit the number of categories.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order' => [
                                        'label' => 'Order',
                                        'type' => 'select',
                                        'default' => 'ordering',
                                        'options' => [
                                            'Alphabetical' => 'title',
                                            'Category Order' => 'ordering',
                                            'Random' => 'rand',
                                        ],
                                    ],
                                    'order_direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'ASC',
                                        'options' => [
                                            'Ascending' => 'ASC',
                                            'Descending' => 'DESC',
                                        ],
                                    ],
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

        $where = 'application_id = ? AND parent = ? AND published = 1';

        if ($args['order'] === 'title') {
            $order = 'name';
        } elseif ($args['order'] === 'rand') {
            $order = 'RAND()';
        } else {
            $order = 'ordering';
        }

        if ($args['order_direction'] === 'DESC') {
            $order .= ' DESC';
        }

        return $zoo->table->category->all([
            'conditions' => [$where, (int) $args['appid'], (int) $args['catid']],
            'order' => $order,
            'offset' => $args['offset'],
            'limit' => $args['limit'],
        ]);
    }
}
