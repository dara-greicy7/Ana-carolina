<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use YOOtheme\Builder\Source;

class CategoryType
{
    /**
     * @param Source       $source
     * @param \Application $application
     * @param string       $type
     *
     * @return array
     */
    public static function config(Source $source, $application, $type)
    {
        $fields = [

            'name' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Name',
                    'filters' => ['limit'],
                ],
            ],

            'description' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Description',
                    'filters' => ['limit'],
                ],
            ],

            'children' => [
                'type' => [
                    'listOf' => $type,
                ],
                'metadata' => [
                    'label' => 'Child Categories',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::children',
                ],
            ],

            'parent' => [
                'type' => $type,
                'metadata' => [
                    'label' => 'Parent Category',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::parent',
                ],
            ],

            'link' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Link',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::link',
                ],
            ],

            'itemCount' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Items Count',
                ],
            ],

            //            'totalItemCount' => [
            //                'type' => 'String',
            //                'metadata' => [
            //                    'label' => 'Total Items Count',
            //                ],
            //            ],

        ];

        $content = ParamsContentType::config($application->getParamsForm()->getXML('category-content'), $application);

        if (!empty($content['fields'])) {

            $fields['content'] = [
                'type' => "{$type}Content",
                'metadata' => [
                    'label' => 'Content',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::content',
                ],
            ];

            $source->objectType("{$type}Content", $content);
        }

        $metadata = [
            'type' => true,
            'label' => 'Category',
        ];

        return compact('fields', 'metadata');
    }

    public static function children(\Category $category)
    {
        if ($category->hasChildren()) {
            return $category->getChildren();
        }

        return $category->app->table->category->all([
            'conditions' => ['parent = ? AND application_id = ? AND published = 1', $category->id, $category->application_id],
        ]);
    }

    public static function parent(\Category $category)
    {
        if ($category->parent == 0) {
            return;
        }

        return $category->app->table->category->first([
            'conditions' => ['id = ? AND published = 1', $category->parent],
        ]);
    }

    public static function link(\Category $category)
    {
        return $category->app->route->category($category);
    }

    public static function itemCount(\Category $category)
    {
        $app = $category->app;
        $db = $app->database;

        // get dates
        $date = $app->date->create();
        $now = $db->Quote($date->toSQL());
        $null = $db->Quote($db->getNullDate());

        $query = 'SELECT COUNT(DISTINCT i.id) as item_count'
            . ' FROM ' . ZOO_TABLE_CATEGORY_ITEM . ' AS ci'
            . ' LEFT JOIN ' . ZOO_TABLE_ITEM . ' AS i USE INDEX (MULTI_INDEX2) ON ci.item_id = i.id'
            . " AND i.{$app->user->getDBAccessString()}"
            . ' AND i.state = 1'
            . " AND (i.publish_up = {$null} OR i.publish_up <= {$now})"
            . " AND (i.publish_down = {$null} OR i.publish_down >= {$now})"
            . " WHERE ci.category_id = {$category->id}"
            . ' GROUP BY ci.category_id';

        return $db->queryResult($query);
    }

    public static function totalItemCount(\Category $category)
    {
        return $category->totalItemCount(); // TODO: Not working
    }

    public static function content(\Category $category)
    {
        return $category;
    }
}
