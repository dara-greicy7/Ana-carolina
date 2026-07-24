<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

/**
 * The ZOOitem Pro helper class.
 */
class mod_zooitemproHelper
{
	/**
	 * Get the filtered and ordered items objects
	 * 
	 * @param Params 	The module params
	 * @param Model 	The Model name to be loaded
	 */
	public static function getItems($params, $model = 'item')
	{
		// init vars
		$zoo = App::getInstance('zoo');
		$cache = $params->find('layout.cache_time', null);

		// set model
		$model = $zoo->zlmodel->getNew($model);

		// get cache, refreshes after one hour automatically
		$cache = $cache ? $zoo->cache->create($zoo->path->path('cache:') . '/mod_zooitempro', true, $cache, 'apc') : null;
		if ($cache && !$cache->check()) {
			$cache = null;
		}

		// get Items
		$result = null;
		$key = md5(serialize($params));

		// use cached IDs to make simpler query
		if ($cache && $ids = $cache->get($key)) 
		{
			// set items ids
			$model->id($ids);

			// set created
			if ($params->find('items.itemfilter.dates.created._filter')) {
				$model->created($params->find('items.itemfilter.dates.created'));
			}

			// set modified
			if ($params->find('items.itemfilter.dates.modified._filter')) {
				$model->modified($params->find('items.itemfilter.dates.modified'));
			}

			// set published up
			if ($params->find('items.itemfilter.dates.published._filter')) {
				$model->published($params->find('items.itemfilter.dates.published'));
			}

			// set published down
			if ($params->find('items.itemfilter.dates.published_down._filter')) {
				$model->published_down($params->find('items.itemfilter.dates.published_down'));
			}
			
			// set order
			$order = array_values((array)$params->find('items.itemorder', array('_itemname')));
			$model->setState('order_by', $order);

			// perform query
			$result = $model->getList();
		} 

		// or perform full query
		else 
		{
			self::setQuery($model, $params);
			$result = $model->getList();
			if ($cache) {
				// save Items IDs to cache
				$ids = array_keys($result);
				$cache->set($key, $ids);
				$cache->save();
			}
		}

		// pretty print of sql
		if (JDEBUG) {
			$find = Array("FROM", "WHERE", "AND", "ORDER BY", "LIMIT", "OR");
			$replace = Array("<br />FROM", "<br />WHERE", "<br />AND", "<br />ORDER BY", "<br />LIMIT", "<br />OR");
			$in = $model->getQuery();
			echo str_replace($find, $replace, $in);
			echo '<br /><br />Total Items: '.count($result);
		}
		
		// return Items objects
		return $result;
	}

	/**
	 * Retreieve Items Objects
	 * 
	 * @param Model The Model Object
	 * @param Params The module params
	 */
	public static function setQuery(&$model, $params)
	{
		// init vars
		$zoo = App::getInstance('zoo');

		// set query
		$model->setState('select', 'DISTINCT a.*');

		// set ids
		if ($ids = $params->find('items._ids')) {
			$model->id($ids);
		}

		// set apps
		if ($apps = $params->find('items._chosenapps')) foreach ($apps as $app) {
			$model->application(array('value'  => $app));
		}

		// set categories
		if ($categories = $params->find('items._chosencats')) {

			// remove Categories with value 0 (ROOT options)
			$categories = array_filter($categories, create_function('$category', 'return $category != 0;'));
			
			// reindex values
			$categories = array_values($categories);

			// set filter
			$model->categories(array('value' => $categories, 'mode' => $params->find('items.categories_mode', 'OR')));
		}

		// set tags
		if ($tags = $params->find('items._chosentags')) {
			$model->tags(array('value' => $tags));
		}

		// set types
		if ($types = $params->find('items._chosentypes')) foreach ($types as $type) {
			$model->type(array('value' => $type));
		}

		// set item state
		$model->state($params->find('items.itemfilter._state', 1));

		// set author/s
		if ($authors = $params->find('items.itemfilter._created_by')) {
			// replace variables
			$authors = preg_replace(array('/\[userid\]/'), array($zoo->user->get()->id), $authors);

			// get array
			$authors = explode(',', $authors);

			// set filter
			foreach ($authors as $author) $model->created_by(trim($author));
		}

		// set frontpage state
		if ($params->find('items.itemfilter._frontpage', 0)) {
			$model->frontpage(true); // use this and not categories to enable for both frontpage AND list of categories search
		}

		// set limits
		$model->setState('limitstart', $params->find('items.itemfilter._offset', 0), true);
		$model->setState('limit', $params->find('items.itemfilter._limit', 0), true);

		// set created
		if ($params->find('items.itemfilter.dates.created._filter')) {
			$model->created($params->find('items.itemfilter.dates.created'));
		}

		// set modified
		if ($params->find('items.itemfilter.dates.modified._filter')) {
			$model->modified($params->find('items.itemfilter.dates.modified'));
		}

		// set published up
		if ($params->find('items.itemfilter.dates.published._filter')) {
			$model->published($params->find('items.itemfilter.dates.published'));
		}

		// set published down
		if ($params->find('items.itemfilter.dates.published_down._filter')) {
			$model->published_down($params->find('items.itemfilter.dates.published_down'));
		}

		// set elements
		foreach ($params->find('items.itemfilter.elements', array()) as $id => $options) {
			
			// init
			$options['id'] = $id;
			$value = @$options['value'];
			$filter = @$options['_filter'];

			// skip if filter not enabled
			if (!isset($filter) || empty($filter)) continue;

			// range workaround
			if (!is_array($value)) {
				$options['from'] = $value;
				$options['to'] = @$options['value_to'];
			}

			// // workaround for period date query, the value must be set
			if (@$options['is_date'] == 1 && $options['type'] == 'period') {
				$options['value'] = true;
			}

			// set filter
			$model->element($options);
		}

		// set order
		$order = array_values((array)$params->find('items.itemorder', array('_itemname')));
		$model->setState('order_by', $order);
	}

	/**
	 * Get the filtered and ordered items objects trough ajax
	 * 
	 * @param Params The module params
	 */
	public static function callback($params)
	{
		return self::getItems($params);
	}
}