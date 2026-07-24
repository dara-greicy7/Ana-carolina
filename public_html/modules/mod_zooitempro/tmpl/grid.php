<?php
/**
 * @package     ZOOitemPro
 * @version     3.3.4
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// load zlux assets
$zoo->zlfw->zlux->loadMainAssets(true);

// get items
$items = mod_zooitemproHelper::getItems($params);

// set renderer
$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), $zoo->path->path('modules:mod_zooitempro')));

// get the renderer layout
$renderer_layout = basename($params->find('layout.renderer_layout', 'default'), '.php');

?>

<?php if (!empty($items)) : ?>
<div class="zx zoo-itempro-grid">

	<?php
		// init vars
		$i = 0;
		$columns = $params->find('layout.columns', 2);

		// render rows
		while ((list($key, $item) = each($items))) {
			if ($i % $columns == 0) echo ($i > 0 ? '</div><div class="uk-grid" data-uk-grid-margin data-uk-grid-match>' : '<div class="uk-grid" data-uk-grid-margin data-uk-grid-match>');
			echo '<div class="uk-width-medium-1-'.$columns.'">'.$renderer->render('item.'.$renderer_layout, compact('item', 'params')).'</div>';
			$i++;
		}

		echo '</div>';
	?>

</div>
<?php else : ?>
<?php echo JText::_('MOD_ZOOITEMPRO_NO_ITEMS_FOUND'); ?>
<?php endif; ?>