<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// only if in zoo item view
if ($zoo->zlfw->enviroment->is('site.com_zoo.item')) {
	$item = $zoo->zlfw->enviroment->params->get('item_id');

	// set renderer
	$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), $zoo->path->path('modules:mod_zooitempro')));

	// get the renderer layout - it's ubication in the params has changed in v3.1, so check both
	$renderer_layout = basename($params->find('layout.renderer_layout', 'default'), '.php');
}

?>

<?php if (!empty($item)) : ?>

<div class="zoo-itempro-current">
	<?php $item = $zoo->table->item->get($item); ?>
	<?php echo $renderer->render('item.'.$renderer_layout, compact('item', 'params')); ?>
</div>

<?php endif; ?>