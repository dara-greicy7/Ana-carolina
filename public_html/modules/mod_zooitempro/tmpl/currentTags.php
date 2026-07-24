<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

if ($zoo->zlfw->enviroment->is('site.com_zoo.item')) {

	$item = $zoo->zlfw->enviroment->params->get('item_id');
	$item = $zoo->table->item->get($item);

	$items_params = $params->get('items');
	$items_params['_chosentags'] = array_merge($params->find('items._chosentags', array()), $item->getTags());
	$params->set('items', $items_params);

	$items = mod_zooitemproHelper::getItems($params);

	$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), $zoo->path->path('modules:mod_zooitempro')));

	$renderer_layout = basename($params->find('layout.renderer_layout', 'default'), '.php');
}

?>

<?php if (!empty($items)) : ?>

<ul class="zoo-itempro-current-tags zoo-current-tags">
	<?php $i = 0; foreach ($items as $item) : ?>
	<li><?php echo $renderer->render('item.'.$renderer_layout, compact('item', 'params')); ?></li>
	<?php $i++; endforeach; ?>
</ul>

<?php else : ?>
<?php echo JText::_('MOD_ZOOITEMPRO_NO_ITEMS_FOUND'); ?>
<?php endif; ?>
