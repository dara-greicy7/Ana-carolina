<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

namespace YOOtheme\Widgetkit\Joomla\Zoopro;

use YOOtheme\Widgetkit\Content\ContentInterface;
use YOOtheme\Widgetkit\Content\Type as BaseType;
use JLoader;

class Type extends BaseType
{
    protected $zoo;

    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->zoo = \App::getInstance('zoo');
    }

    public function getItems(ContentInterface $content)
    {
        $items = parent::getItems($content);

        $params = $content->getData();
        $params['order'] = array_filter(array_values($params['order']));
        $params = $this->zoo->data->create($params);

        foreach ($this->renderZooItems($this->zoo->module->getItems($params), $params) as $item) {
            $items->add($item);
        }

        return $items;
    }

    public function renderZooItems($items, $params)
    {
        JLoader::register('WidgetkitzlRenderer', $this->app['locator']->find('plugins/content/zoopro/renderer/widgetkit.php'));

        $renderer = $this->zoo->renderer->create('widgetkitzl')
            ->addPath(array($this->zoo->path->path('component.site:'), $this->app['locator']->find('plugins/content/zoopro')))
            ->setLayout($params->get('mapping_layout'));

        $result = array();
        foreach ($items as $i => $item) {

            $renderer->setItem($item);

            $data = array();
            $mapping = array_merge(array(
                'title'      => 'title',
                'content'    => 'content',
                'media'      => 'media',
                'media2'     => 'media2',
                'location'   => 'location',
                'link'       => 'link',
                'date'       => 'date',
                'author'     => 'author',
                'categories' => 'categories',
                'tags'       => 'tags',
                'custom1'    => 'custom1',
                'custom2'    => 'custom2',
                'custom3'    => 'custom3',
                'custom4'    => 'custom4',
                'custom5'    => 'custom5',
            ), $params->find("mapping.{$item->getType()->id}", array()));

            foreach ($mapping as $field => $value) {
                $data[$field] = $renderer->renderPosition($value);
            }

            // validate fields
            $data['date']       = isset($data['date']) && strtotime($data['date']) ? $data['date'] : null;
            $data['author']     = isset($data['author']) && is_string($data['author']) ? $data['author'] : null;
            $data['categories'] = isset($data['categories']) && is_array($data['categories']) ? $data['categories'] : null;

            if (isset($data['content']) && is_array($data['content'])) {
                $data['content'] = implode(', ', $data['content']);
            }

            $result[] = $data;
        }

        return $result;
    }

    public function getFormData()
    {
        $result = array();

        foreach ($this->zoo->application->getApplications() as $app) {

            $data = array('id' => $app->id, 'name' => $app->name, 'types' => array(), 'categories' => array());

            foreach ($app->getTypes() as $type) {

                $data['types'][$type->id] = array(
                    'id'       => $type->id,
                    'name'     => $type->getName(),
                    'elements' => array_values(array_map(function ($el) {
                        return array(
                            'id'        => $el->identifier,
                            'name'      => $el->config->name ? $el->config->name : $el->getMetaData('name'),
                            'type'      => $el->getElementType(),
                            'orderable' => $el->getMetaData('orderable') == 'true',
                            'core'      => $el->getMetaData('group') == 'Core',
                            'group'     => $el->getMetaData('group')
                        );
                    }, $type->getElements() + $type->getCoreElements()))
                );
            }

            $data['categories'][] = array('id' => '0', 'name' => html_entity_decode('&#8226; Frontpage', ENT_QUOTES, 'UTF-8'));
            foreach ($this->zoo->tree->buildList(0, $app->getCategoryTree(), array(), '-&nbsp;', '.&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;') as $category) {
                $data['categories'][] = array(
                    'id'   => $category->id,
                    'name' => html_entity_decode($category->treename, ENT_QUOTES, 'UTF-8')
                );
            }

            $result[$app->id] = $data;
        }

        return $result;
    }
}
