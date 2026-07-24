<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\PaginationObject;

class TemplateListener
{
    public static function matchItemTemplate($view)
    {
        if (!static::isView($view, 'item')) {
            return;
        }

        $item = $view->item;

        return [
            'type' => "com_zoo.{$view->application->id}.{$item->getType()->id}",
            'query' => [
                'catid' => $item->getRelatedCategoryIds(true),
                'tag' => $item->getTags(),
            ],
            'params' => [
                'item' => $item,
                'pagination' => function () use ($item) {
                    $element = $item->getElement('_itemprevnext');

                    if ($element && $links = $element->getValue()) {
                        return [
                            'previous' => $links['prev_link'] ? new PaginationObject(Text::_('JPREV'), '', null, $links['prev_link']) : null,
                            'next' => $links['next_link'] ? new PaginationObject(Text::_('JNEXT'), '', null, $links['next_link']) : null,
                        ];
                    }
                },
            ],
            'editUrl' => $item->canEdit()
                ? $item->app->route->submission($view->application->getItemEditSubmission(), $item->type, null, $item->id, 'itemedit')
                : null,
        ];
    }

    public static function matchCategoryTemplate($view)
    {
        if (!static::isView($view, 'category')) {
            return;
        }

        return [
            'type' => "com_zoo.{$view->application->id}.category",
            'query' => [
                'catid' => $view->category->id,
                'pages' => $view->pagination->current() === 1 ? 'first' : 'except_first',
            ],
            'params' => [
                'category' => $view->category,
                'items' => $view->items,
                'pagination' => new Pagination($view->pagination, $view->pagination_link),
            ],
        ];
    }

    public static function matchFrontpageTemplate($view)
    {
        if (!static::isView($view, 'frontpage')) {
            return;
        }
        return [
            'type' => "com_zoo.{$view->application->id}.frontpage",
            'query' => [
                'pages' => $view->pagination->current() === 1 ? 'first' : 'except_first',
            ],
            'params' => [
                'application' => $view->application,
                'items' => $view->items,
                'pagination' => new Pagination($view->pagination, $view->pagination_link),
            ],
        ];
    }

    public static function matchTagTemplate($view)
    {
        if (!static::isView($view, 'tag')) {
            return;
        }

        return [
            'type' => "com_zoo.{$view->application->id}.tag",
            'query' => [
                'pages' => $view->pagination->current() === 1 ? 'first' : 'except_first',
            ],
            'params' => [
                'tag' => $view->tag,
                'items' => $view->items,
                'application' => $view->application,
                'pagination' => new Pagination($view->pagination, $view->pagination_link),
            ],
        ];
    }

    protected static function isView($view, $task)
    {
        return $view instanceof \AppView && $view->getName() === $task && $view->getLayout() === $task;
    }
}
