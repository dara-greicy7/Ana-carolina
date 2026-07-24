<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use YOOtheme\Builder\Joomla\Zoo\StrHelper;
use YOOtheme\Event;
use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\Builder\Joomla\Zoo\ApplicationHelper;
use YOOtheme\Builder\Source;
use YOOtheme\Path;
use YOOtheme\Str;
use YOOtheme\View;

class ItemType
{
    protected static $skip = [
        'addthis', 'disqus', 'flickr', 'intensedebate', 'itemaccess', 'itemedit', 'itemfrontpage', 'itemprevnext', 'itemprint', 'itemsearchable', 'itemstate', 'joomlamodule', 'socialbookmarks', 'socialbuttons',
    ];

    /**
     * @param Source $source
     * @param string $name
     * @param \Type  $type
     *
     * @return array
     */
    public static function config(Source $source, $name, \Type $type)
    {
        $elements = array_merge(
            ['_itemname' => '', '_itempublish_up' => '', '_itempublish_down' => '', '_itemcreated' => '', '_itemmodified' => '', '_itemlink' => ''],
            $type->getCoreElements(),
            $type->getElements()
        );

        foreach ($elements as $element) {

            // skip these elements
            if (in_array($element->getElementType(), self::$skip)) {
                continue;
            }

            $name = $element->config->name;
            $field = StrHelper::toFieldName($name);

            if (!$field) {
                continue;
            }

            $config = [
                'type' => 'String',
                'group' => $element->getMetaData('group'),
                'args' => [],
                'metadata' => [
                    'label' => $name,
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::resolve',
                ],
            ];

            $elementType = Str::camelCase($element->getElementType(), true);

            $config = is_callable($callback = [__CLASS__, "config{$elementType}"])
                ? $callback($element, $config, $source, $name)
                : static::configElement($element, $config);

            $config = Event::emit('source.com_zoo.item.field|filter', $config, $element, $source, $name);

            if ($config) {
                $fields[$field] = $config;
            }

            if (is_callable($callback = [__CLASS__, "config{$elementType}String"])) {
                $config = $callback($element, $config, $source, $name);

                $config = Event::emit('source.com_zoo.item.field|filter', $config, $element, $source, $name);

                if ($config) {
                    $fields[$field . 'String'] = $config;
                }
            }

        }

        Arr::splice($fields, 5, 0, self::getAdditionalFields());

        $metadata = [
            'type' => true,
            'label' => $type->getName(),
        ];
        return compact('fields', 'metadata');
    }

    protected static function configElement(\Element $element, array $config)
    {
        if (in_array($element->getElementType(), ['itemcreated', 'itempublish_up', 'itempublish_down', 'itemmodified'])) {
            $config['metadata']['filters'] = ['date'];
        } elseif (!in_array($element->getElementType(), ['itemlink', 'itemcommentslink'])) {
            $config['metadata']['filters'] = ['limit'];
        }

        if (self::isMultiple($element)) {
            return ['type' => ['listOf' => 'ValueField']] + $config;
        }

        return $config;
    }

    protected static function configGroup(\Element $element, $config, $source, $name)
    {
        $fields = [];

        foreach ($element->config->get('element', []) as $field) {
            $fields[] = [
                'type' => 'String',
                'name' => StrHelper::toFieldName($field['name']),
                'metadata' => [
                    'label' => $field['name'],
                    'filters' => !in_array($field['type'], ['image']) ? ['limit'] : [],
                ],
            ];
        }

        if ($fields) {

            $name = Str::camelCase([$name, $element->config->name], true);
            $source->objectType($name, compact('fields'));

            if (self::isMultiple($element)) {
                return ['type' => ['listOf' => $name]] + $config;
            }

            return ['type' => $name] + $config;
        }
    }

    protected static function configTextarea(\Element $element, array $config)
    {
        $config['metadata']['filters'] = ['limit'];

        if (self::isMultiple($element)) {
            $config['args']['display'] = [
                'type' => 'String',
            ];
            $config['metadata']['arguments']['display'] = [
                'label' => 'Display',
                'type' => 'select',
                'default' => 'all',
                'options' => [
                    'All' => 'all',
                    'First' => 'first',
                    'All without first' => 'all_without_first',
                ],
            ];
        }

        return $config;
    }

    protected static function configDownload(\Element $element, array $config)
    {
        return ['type' => 'ZooDownload'] + $config;
    }

    protected static function configEmail(\Element $element, array $config)
    {
        $config['metadata']['filters'] = ['limit'];

        if (self::isMultiple($element)) {
            return ['type' => ['listOf' => 'ZooEmail']] + $config;
        }
        return ['type' => 'ZooEmail'] + $config;
    }

    protected static function configDate(\Element $element, array $config)
    {
        $config['metadata']['filters'] = ['date'];

        if (self::isMultiple($element)) {
            return ['type' => ['listOf' => 'ZooDate']] + $config;
        }

        return $config;
    }

    protected static function configGallery(\Element $element, array $config)
    {
        return ['type' => ['listOf' => 'ZooGallery']] + $config;
    }

    protected static function configGoogleMaps(\Element $element, array $config)
    {
        return ['type' => 'ZooGoogleMaps'] + $config;
    }

    protected static function configImage(\Element $element, array $config)
    {
        return ['type' => 'ZooImage'] + $config;
    }

    protected static function configLink(\Element $element, array $config)
    {
        if (self::isMultiple($element)) {
            return ['type' => ['listOf' => 'ZooLink']] + $config;
        }
        return ['type' => 'ZooLink'] + $config;
    }

    protected static function configRating(\Element $element, array $config)
    {
        return ['type' => 'ZooRating'] + $config;
    }

    protected static function configRelatedItems(\Element $element, array $config)
    {
        $selected_types = $element->config->get('selectable_types', []);

        if (empty($selected_types)) {
            return;
        }

        $appId = $element->config->get('app_id') ?: $element->getType()->getApplication()->id;
        $application = ApplicationHelper::getApplication($appId);

        if ($application) {
            return ['type' => ['listOf' => Str::camelCase(['Zoo', $application->application_group, $selected_types[0]], true)]] + $config;
        }
    }

    protected static function configItemAuthor($element, array $config)
    {
        return ['type' => 'User'] + $config;
    }

    protected static function configItemCategory(\Element $element, array $config)
    {
        return ['type' => ['listOf' => static::getCategoryType($element)]] + $config;
    }

    protected static function configItemPrimaryCategory(\Element $element, array $config)
    {
        return ['type' => static::getCategoryType($element)] + $config;
    }

    protected static function configItemTag(\Element $element, array $config)
    {
        return ['type' => ['listOf' => 'ZooTag']] + $config;
    }

    protected static function configRelatedCategories(\Element $element, array $config)
    {
        $type = static::getCategoryType($element);
        if (self::isMultiple($element)) {
            return ['type' => ['listOf' => $type]] + $config;
        }
        return ['type' => $type] + $config;
    }

    protected static function configSelect(\Element $element, array $config)
    {
        return self::configOption($element, $config, $element->config->get('multiple'));
    }

    protected static function configSelectString(\Element $element, array $config)
    {
        return ['type' => 'ZooChoiceFieldString'] + $config;
    }

    protected static function configCheckbox(\Element $element, array $config)
    {
        return self::configOption($element, $config, true);
    }

    protected static function configCheckboxString(\Element $element, array $config)
    {
        return ['type' => 'ZooChoiceFieldString'] + $config;
    }

    protected static function configRadio(\Element $element, array $config)
    {
        return self::configOption($element, $config);
    }

    protected static function configOption(\Element $element, array $config, $multiple = false)
    {
        return ['type' => $multiple ? ['listOf' => 'ChoiceField'] : 'ChoiceField'] + $config;
    }

    public static function resolve($item, $args, $ctx, $info)
    {
        $name = str_replace('String', '', $info->fieldName);
        $element = static::getElement($name, $item);

        if (!$element) {
            return;
        }

        $elementType = Str::camelCase($element->getElementType(), true);
        if (is_callable($callback = [__CLASS__, "resolve{$elementType}"])) {
            return $callback($element, $args);
        }

        return static::resolveElement($element);
    }

    public static function resolveElement($element)
    {
        $value = $element->getValue();

        if (!self::hasMultipleValue($element)) {
            return $value;
        }

        $value = (array) $value;

        return static::isMultiple($element)
            ? array_map(function ($value) {
                return is_scalar($value)
                    ? compact('value')
                    : $value;
            }, $value)
            : array_pop($value);
    }

    public static function resolveGroup($element, $args)
    {
        $values = array_map(function ($vals) {

            $values = [];

            foreach ($vals as $name => $value) {
                $values[StrHelper::toFieldName($name)] = $value;
            }

            return $values;

        }, $element->getValue() ?: []);

        if (static::isMultiple($element)) {
            return $values;
        }

        return array_pop($values);
    }

    public static function resolveTextarea($element, $args)
    {
        return implode('', $element->getValue($args));
    }

    public static function resolveItemTag($element)
    {
        $application = $element->getType()->getApplication()->id;

        return array_map(function ($tag) use ($application) {
            return (object) [
                'name' => $tag,
                'application_id' => $application,
            ];
        }, $element->getValue());
    }

    protected static function hasMultipleValue(\Element $element)
    {
        return $element instanceof \ElementRepeatable
            || $element instanceof \ElementGallery
            || $element instanceof \ElementRelatedItems
            || $element instanceof \ElementRadio
            || $element->config->has('multiselect')
            || $element->config->has('multiple');
    }

    protected static function isMultiple(\Element $element)
    {
        return $element instanceof \ElementRepeatable
            && $element->config->get('repeatable')
            || $element instanceof \ElementGallery
            || $element instanceof \ElementRelatedItems
            || $element->config->get('multiselect')
            || $element->config->get('multiple');
    }

    protected static function getAdditionalFields()
    {
        return [

            'metaString' => [
                'type' => 'String',
                'args' => [
                    'format' => [
                        'type' => 'String',
                    ],
                    'separator' => [
                        'type' => 'String',
                    ],
                    'link_style' => [
                        'type' => 'String',
                    ],
                    'show_publish_date' => [
                        'type' => 'Boolean',
                    ],
                    'show_author' => [
                        'type' => 'Boolean',
                    ],
                    'show_category' => [
                        'type' => 'Boolean',
                    ],
                    'date_format' => [
                        'type' => 'String',
                    ],
                ],
                'metadata' => [
                    'label' => 'Meta',
                    'arguments' => [

                        'format' => [
                            'label' => 'Format',
                            'description' => 'Display the meta text in a sentence or a horizontal list.',
                            'type' => 'select',
                            'default' => 'list',
                            'options' => [
                                'List' => 'list',
                                'Sentence' => 'sentence',
                            ],
                        ],
                        'separator' => [
                            'label' => 'Separator',
                            'description' => 'Set the separator between fields.',
                            'default' => '|',
                            'enable' => 'arguments.format === "list"',
                        ],
                        'link_style' => [
                            'label' => 'Link Style',
                            'description' => 'Set the link style.',
                            'type' => 'select',
                            'default' => '',
                            'options' => [
                                'Default' => '',
                                'Muted' => 'link-muted',
                                'Text' => 'link-text',
                                'Heading' => 'link-heading',
                                'Reset' => 'link-reset',
                            ],
                        ],
                        'show_publish_date' => [
                            'label' => 'Display',
                            'description' => 'Show or hide fields in the meta text.',
                            'type' => 'checkbox',
                            'default' => true,
                            'text' => 'Show date',
                        ],

                        'show_author' => [
                            'type' => 'checkbox',
                            'default' => true,
                            'text' => 'Show author',
                        ],
                        'show_category' => [
                            'type' => 'checkbox',
                            'default' => true,
                            'text' => 'Show category',
                        ],
                        'date_format' => [
                            'label' => 'Date Format',
                            'description' => 'Select a predefined date format or enter a custom format.',
                            'type' => 'data-list',
                            'default' => '',
                            'options' => [
                                'Aug 6, 1999 (M j, Y)' => 'M j, Y',
                                'August 06, 1999 (F d, Y)' => 'F d, Y',
                                '08/06/1999 (m/d/Y)' => 'm/d/Y',
                                '08.06.1999 (m.d.Y)' => 'm.d.Y',
                                '6 Aug, 1999 (j M, Y)' => 'j M, Y',
                                'Tuesday, Aug 06 (l, M d)' => 'l, M d',
                            ],
                            'enable' => 'arguments.show_publish_date',
                            'attrs' => [
                                'placeholder' => 'Default',
                            ],
                        ],
                    ],
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::metaString',
                ],
            ],

            'categoryString' => [
                'type' => 'String',
                'args' => [
                    'separator' => [
                        'type' => 'String',
                    ],
                    'show_link' => [
                        'type' => 'Boolean',
                    ],
                    'link_style' => [
                        'type' => 'String',
                    ],
                ],
                'metadata' => [
                    'label' => 'Categories',
                    'arguments' => [
                        'separator' => [
                            'label' => 'Separator',
                            'description' => 'Set the separator between categories.',
                            'default' => ', ',
                        ],
                        'show_link' => [
                            'label' => 'Link',
                            'type' => 'checkbox',
                            'default' => true,
                            'text' => 'Show link',
                        ],
                        'link_style' => [
                            'label' => 'Link Style',
                            'description' => 'Set the link style.',
                            'type' => 'select',
                            'default' => '',
                            'options' => [
                                'Default' => '',
                                'Muted' => 'link-muted',
                                'Text' => 'link-text',
                                'Heading' => 'link-heading',
                                'Reset' => 'link-reset',
                            ],
                            'enable' => 'arguments.show_link',
                        ],
                    ],
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::categoryString',
                ],
            ],

            'tagString' => [
                'type' => 'String',
                'args' => [
                    'separator' => [
                        'type' => 'String',
                    ],
                    'show_link' => [
                        'type' => 'Boolean',
                    ],
                    'link_style' => [
                        'type' => 'String',
                    ],
                ],
                'metadata' => [
                    'label' => 'Tags',
                    'arguments' => [

                        'separator' => [
                            'label' => 'Separator',
                            'description' => 'Set the separator between tags.',
                            'default' => ', ',
                        ],
                        'show_link' => [
                            'label' => 'Link',
                            'type' => 'checkbox',
                            'default' => true,
                            'text' => 'Show link',
                        ],
                        'link_style' => [
                            'label' => 'Link Style',
                            'description' => 'Set the link style.',
                            'type' => 'select',
                            'default' => '',
                            'options' => [
                                'Default' => '',
                                'Muted' => 'link-muted',
                                'Text' => 'link-text',
                                'Heading' => 'link-heading',
                                'Reset' => 'link-reset',
                            ],
                            'enable' => 'arguments.show_link',
                        ],

                    ],
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::tagString',
                ],
            ],

        ];
    }

    public static function metaString(\Item $item, array $args)
    {
        $args += ['format' => 'list', 'separator' => '|', 'link_style' => '', 'show_publish_date' => true, 'show_author' => true, 'show_category' => true, 'date_format' => ''];

        return app(View::class)->render(Path::get('../../templates/meta'), compact('item', 'args'));
    }

    public static function categoryString(\Item $item, array $args)
    {
        $categories = $item->getRelatedCategories(true);
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];

        return app(View::class)->render(Path::get('../../templates/categories'), compact('item', 'categories', 'args'));
    }

    public static function tagString(\Item $item, array $args)
    {
        $tags = $item->getTags();
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];

        return app(View::class)->render(Path::get('../../templates/tags'), compact('item', 'tags', 'args'));
    }

    /**
     * @param string $name
     * @param \Item  $item
     *
     * @return \Element|null
     */
    protected static function getElement($name, \Item $item)
    {
        foreach ([$item->getCoreElements(), $item->getElements()] as $elements) {
            foreach ($elements as $element) {
                if ($name === StrHelper::toFieldName($element->config->name)) {
                    return $element;
                }
            }
        }
    }

    protected static function getCategoryType(\Element $element)
    {
        return Str::camelCase(['Zoo', $element->getType()->getApplication()->application_group, 'Category'], true);
    }
}
