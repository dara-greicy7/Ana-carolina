<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

class WidgetkitzlRenderer extends ItemRenderer
{
    protected $cache;

    public function __destruct()
    {
        if ($this->cache && $this->cache->check()) {
            $this->cache->save();
        }
    }

    protected $mapping = array(
        'location' => array('googlemaps', 'googlemapspro'),
        'link'     => array('link', 'linkpro'),
        'date'     => array('date', 'datepro'),
        'cats'     => array('relatedcategories', 'relatedcategoriespro')
    );

    public function getMapping()
    {
        return $this->mapping;
    }

    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
        return $this;
    }

    public function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    public function renderPosition($position, $args = array())
    {
        $elements = array();
        $output   = '';

        foreach ($this->_getConfigPosition($position) as $index => $data) {
            if ($element = $this->_item->getElement($data['element'])) {

                if (!$element->canAccess()) {
                    continue;
                }

                $data['_layout'] = $this->_layout;
                $data['_position'] = $position;
                $data['_index'] = $index;

                $params = array_merge($data, $args);

                if ($element->hasValue($this->app->data->create($params))) {

                    $render = true;
                    $this->app->event->dispatcher->notify($this->app->event->create($this->_item, 'element:beforedisplay', array('render' => &$render, 'element' => $element, 'params' => $params)));

                    if ($render) {
                        $elements[] = compact('element', 'params');
                    }
                }
            }
        }

        foreach ($elements as $i => $data) {
            $element = $data['element'];
            $params = $this->app->data->create($data['params']);

            if (is_subclass_of($element, 'ElementRepeatable')) {
                $element->seek(0);
            }

            switch ($position) {
                case 'title':

                    if ($element->getElementType() == 'itemname') {

                        $output = $this->_item->name;

                    } else {
                        $output = parent::renderPosition('title');
                    }

                    break 2;

                case 'media':
                case 'media2':
                    // check the file or valid resources
                    if (($path = $element->get('file')) || (method_exists($element, 'getValidResources') && $valid_resources = $element->getValidResources())) {
                        if ($element instanceof ElementImagePro) {
                            $valid_resources = $path ? $element->getValidResources($path) : $valid_resources;
                            $output = $this->getResizedImage(JPATH_ROOT . '/' . array_shift($valid_resources), $params);
                        } else {
                            $output = $path;
                        }
                    } else if ($url = $element->get('url')) {
                        $output = $url;
                    } else {
                        $output = strip_tags(parent::renderPosition('media'));
                    }

                    $output = trim($output);
                    break 2;

                case 'link':

                    if ($this->isType($element, 'link')) {
                        $output = $element->get('value');
                    } else if ($element->getElementType() == 'itemlink') {
                        $output = $this->app->route->item($this->_item);
                    } else {
                        $output = parent::renderPosition('link');
                    }

                    $output = trim($output);
                    break 2;

                case 'location':

                    $locationData = $this->isType($element, 'location') ? $element->get('location') : parent::renderPosition('location');
                    try {

                        $coordinates = $this->app->googlemaps->locate($locationData, $this->getCache());

                    } catch (GooglemapsHelperException $e) {
                        $this->app->system->application->enqueueMessage($e, 'notice');
                        continue;
                    }

                    $output = $coordinates;
                    break 2;

                case 'date':

                    if ($this->isType($element, 'date')) {
                        $output = $element->get('value');
                    } elseif ($element->getElementType() == 'itemcreated') {
                        $output = $element->getItem()->created;
                    } else if ($element->getElementType() == 'itemmodified') {
                        $output = $element->getItem()->modified;
                    } else {
                        $output = parent::renderPosition('date');
                    }

                    $output = trim($output);
                    break 2;

                case 'author':

                    if ($element->getElementType() == 'itemauthor') {

                        $author = $element->getItem()->created_by_alias;
                        $user   = $this->app->user->get($element->getItem()->created_by);
                        $output = empty($author) && $user ? $user->name : $author;

                    } else {
                        $output = parent::renderPosition('author');
                    }

                    $output = htmlspecialchars_decode(trim($output));
                    break 2;

                case 'categories':

                    if ($this->isType($element, 'cats')) {

                        $categories = array();
                        foreach ($this->app->table->category->getById($element->get('category', array()), true) as $cat) {
                            $categories[$cat->name] = $this->app->route->category($cat);
                        }

                        $output = $categories;

                    } elseif ($element->getElementType() == 'itemcategory') {

                        $categories = array();
                        foreach ($element->getItem()->getRelatedCategories(true) as $cat) {
                            $categories[$cat->name] = $this->app->route->category($cat);
                        }

                        $output = $categories;

                    } else {
                        $output = parent::renderPosition('categories');
                    }

                    break 2;

                case 'tags':

                    if ($element->getElementType() == 'itemtag') {

                        $output = $this->_item->getTags();

                    } else {
                        $output = parent::renderPosition('tags');
                        $output = explode(', ', trim($output));
                    }

                    break 2;

                default:
                    // custom 1, 2 etc. positions for files pro elements should return array
                    if ($position != 'content' && $element instanceof ElementFilesPro) {
                        if (($path = $element->get('file')) || ($valid_resources = $element->getValidResources())) {
                            $values = $element->getRawRenderedValues($params);
                            foreach ($values['result'] as $valid_resource) {
                                $value = null;
                                if ($element instanceof ElementImagePro) {
                                    $value = $this->getResizedImage($this->app->path->path($valid_resource['sourcefile']), $params);
                                } elseif ($element instanceof ElementMediaPro) {
                                    $value = $valid_resource->getURL();
                                } elseif ($element instanceof ElementDownloadPro) {
                                    $value = $valid_resource['file']->getURL();
                                }
                                $output[] = $value;
                            }
                        }
                    } else {
                        $output = trim(parent::renderPosition($position));
                    }
            }
        }

        // defaults
        if (empty($output)) {

            switch ($position) {
                case 'title':
                    $output = $this->_item->name;
                    break;
            }

        }

        return $output;
    }

    public function isType($element, $type)
    {
        return isset($this->mapping[$type]) && in_array($element->getElementType(), $this->mapping[$type]);
    }

    public function getCache()
    {
        if (null === $this->cache) {
            $this->cache = $this->app->cache->create($this->app->path->path('cache:').'/geocode_cache');
        }

        return $this->cache;
    }

    /**
     * Get resized image path
     */
    protected function getResizedImage($file, $params) {
        $width        = $params->find('specific._width', 0);
        $height       = $params->find('specific._height', 0);
        $ac           =  $params->find('specific._avoid_cropping', 0);
        $avoid_resize_small = $params->find('specific._avoid_resize_small', 0);

        return $this->app->path->relative( $this->app->zlfw->resizeImage($file, $width, $height, $ac, null, true, $avoid_resize_small) );
    }
}
