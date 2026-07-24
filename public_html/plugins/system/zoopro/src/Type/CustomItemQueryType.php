<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use YOOtheme\Builder\Joomla\Zoo\StrHelper;
use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Zoo;

class CustomItemQueryType
{
    /**
     * @param \Type          $type
     * @param mixed          $name
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($name, \Type $type, $applications)
    {
        $application = $type->getApplication();
        $appOptions = array_column($applications, 'id', 'name');

        $orderOptions = [];
        foreach ($type->getElements() as $element) {
            if ($element->getMetaData('orderable') == 'true') {
                $orderOptions[$element->config->name ?: $element->getMetaData('name')] = $element->identifier;
            }
        }

        $singularLower = Str::lower($type->getName());
		$pluralName = StrHelper::toPlural($type->getName(), '%s items');
        $pluralLower = Str::lower($pluralName);
        $pluralUpper = Str::titleCase($pluralName);

        return [
            'fields' => [

                Str::camelCase(['custom', $type->id]) => [
                    'type' => $name,
                    'args' => [
                        'appid' => [
                            'type' => 'String',
                        ],
                        'id' => [
                            'type' => 'String',
                        ],
                        'categories' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'tags' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'frontpage' => [
                            'type' => 'Boolean',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                        'order_alphanum' => [
                            'type' => 'Boolean',
                        ],
                    ],
                    'metadata' => [
                        'label' => "Custom ZOO {$application->getMetaData('name')} {$type->getName()}",
                        'group' => 'ZOO',
                        'fields' => [
                            'appid' => [
                                'label' => 'Application',
                                'description' => 'Only items from the selected application are loaded.',
                                'type' => 'select',
                                'options' => $appOptions,
								'defaultIndex' => 0,
                                'attrs' => [
                                    'class' => 'uk-height-small',
                                ],
                                'show' => count($appOptions) > 1,
                            ],
                            'id' => [
                                'label' => 'Select Manually',
                                'description' => "Pick a {$singularLower} manually or use filter options to specify which {$singularLower} should be loaded dynamically.",
                                'type' => 'select-item',
                                'module' => 'zoo',
                                'item_type' => $type->id,
                                'labels' => [
                                    'type' => $type->getName(),
                                ],
                            ],
                            'categories' => [
                                'label' => 'Limit by Categories',
                                'description' => "The {$singularLower} is only loaded from the selected categories. {$pluralUpper} from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.",
                                'type' => 'select',
                                'default' => [],
								'options' => [
									['evaluate' => 'config.zoo[appid]["categories"]'],
								],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                                'enable' => '!id',
                            ],
                            'tags' => [
                                'label' => 'Limit by Tags',
                                'description' => "The {$singularLower} is only loaded from the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.",
								'type' => 'select',
								'default' => [],
								'options' => [
									['evaluate' => 'config.zoo[appid]["tags"]'],
								],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                                'enable' => '!id',
                            ],
                            'frontpage' => [
                                'label' => "Limit by Frontpage {$pluralUpper}",
                                'type' => 'checkbox',
                                'text' => "Load frontpage {$pluralLower} only",
                                'enable' => '!id',
                            ],
                            'offset' => [
                                'label' => 'Start',
                                'description' => "Set the starting point and limit the number of {$pluralUpper}.",
                                'type' => 'number',
                                'default' => 0,
                                'modifier' => 1,
                                'attrs' => [
                                    'min' => 1,
                                    'required' => true,
                                ],
                                'enable' => '!id',
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order' => [
                                        'label' => 'Order',
                                        'type' => 'select',
                                        'default' => '_itempublish_up',
                                        'options' => [
                                            'Published' => '_itempublish_up',
                                            'Created' => '_itemcreated',
                                            'Modified' => '_itemmodified',
                                            'Alphabetical' => '_itemname',
                                            'Hits' => '_itemhits',
                                            'Random' => '_random',
                                        ] + $orderOptions,
                                        'enable' => '!id',
                                    ],
                                    'order_direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'DESC',
                                        'options' => [
                                            'Ascending' => 'ASC',
                                            'Descending' => 'DESC',
                                        ],
                                        'enable' => '!id',
                                    ],
                                ],
                            ],
                            'order_alphanum' => [
                                'text' => 'Alphanumeric Ordering',
                                'type' => 'checkbox',
                                'enable' => '!id',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'type' => $type->id,
                            ],
                        ],
                    ],
                ],

            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        $args += ['id' => 0, 'limit' => 1];

        /**
         * @var Zoo $zoo
         */
        $zoo = app(Zoo::class);

        if (!empty($args['id'])) {
            return $zoo->table->item->get($args['id']);
        }

        $items = CustomItemsQueryType::resolve($root, $args);
        return array_pop($items);
    }
}
