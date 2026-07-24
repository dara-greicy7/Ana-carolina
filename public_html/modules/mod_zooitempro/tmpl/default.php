<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// get items
$items = mod_zooitemproHelper::getItems($params);

// set renderer
$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), $zoo->path->path('modules:mod_zooitempro')));

// get the renderer layout - it's ubication in the params has changed in v3.1, so check both
$renderer_layout = basename($params->find('layout.renderer_layout', $params->get('renderer_layout', 'default')), '.php');

?>

<?php if (!empty($items)) : ?>

<ul class="zoo-itempro-default zoo-default">
	<?php $i = 0; foreach ($items as $item) : ?>
	<li><?php echo $renderer->render('item.'.$renderer_layout, compact('item', 'params')); ?></li>
	<?php $i++; endforeach; ?>
</ul>

<?php else : ?>
<?php echo JText::_('MOD_ZOOITEMPRO_NO_ITEMS_FOUND'); ?>
<?php endif; ?>