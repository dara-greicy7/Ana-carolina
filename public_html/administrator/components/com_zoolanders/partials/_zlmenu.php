<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

$menu = $this->app->zl->menu->get('nav')
	->addFilter(array('ZooMenuFilter', 'activeFilter'))
	->applyFilter();

echo '<nav class="uk-navbar tm-navbar">'.$menu->render(array('ZlMenuDecorator', 'index')).'</nav>';

/*
	Class: ZooMenuFilter
		Filter for menu class.
*/
class ZooMenuFilter {

	public static function activeFilter(AppMenuItem $item) {

		// init vars
		$id          = '';
		$app		 = App::getInstance('zoo');
		$controller  = $app->request->getWord('controller');
		$task 		 = $app->request->getWord('task');
		$classes     = array();

		// set id
		if ($controller == 'zoolanders') {
			$id = $task;
		} else {
			$id = $controller;
		}

		// save current class attribute
		$class = $item->getAttribute('class');
		if (!empty($class)) {
			$classes[] = $class;
		}

		// set active class
		if ($item->getId() == $id || $item->hasChild($id, true)) {
			$classes[] = 'uk-active';
		}

		// replace the old class attribute
		$item->setAttribute('class', implode(' ', $classes));
	}
}

/**
 * A menu decorator class
 */
class ZlMenuDecorator {

    /**
     * Add item index and level to class attribute
     *
     * @param  SimpleXMLElement $node The node to add the index and level to
     * @param  array            $args Callback arguments
     */
	public static function index(SimpleXMLElement $node, $args) {

		if ($node->getName() == 'ul') {

			// set up level
			$level = ($args['level'] / 2) + 1;

			if ($level > 1) {
				$node->addAttribute('class', 'uk-nav uk-nav-navbar');
			} else {
				$node->addAttribute('class', 'uk-navbar-nav');
			}
		}

		if ($node->getName() == 'li') {

			$css = '';

			// parent
			if (isset($node->div)) {
				$css .= ' uk-parent';
				$node->addAttribute('data-uk-dropdown', '');
			}

			// add li css classes
			$node->attributes()->class = trim($node->attributes()->class.$css);

			// add a/span css classes
			$children = $node->children();
			if ($firstChild = $children[0]) {
				$firstChild->addAttribute('class', trim($firstChild->attributes()->class.$css));
			}
		}

		unset($node->attributes()->icon);

	}
}