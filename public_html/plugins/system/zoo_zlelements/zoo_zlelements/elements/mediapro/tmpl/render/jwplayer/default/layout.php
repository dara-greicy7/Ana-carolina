<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$skin		= $params->find('layout._skin');
$skin   	= $this->app->path->url("elements:mediapro/assets/players/jwplayer/skins/$skin/$skin.xml");
$cfront 	= $params->find('layout._cloudfront', '');
$autoplay 	= $params->find('layout._autoplay', 0);

?>

<div id="jwplayer_<?php echo $id ?>">
	<div style="text-align: center;"><?php echo JText::_('PLG_ZLELEMENTS_MP_NO_JAVASCRIPT') ?></div>
</div>

<script type="text/javascript">
jQuery(function($){

	jwplayer("jwplayer_<?php echo $id ?>").setup({

		// play list
		playlist: <?php echo json_encode($playlist) ?>,
		<?php if ($size = $params->find('layout._playlist')) : ?>
		listbar: {
			position: "<?php echo $params->find('layout._playlist_position', 'none') ?>"
			<?php if ($size = $params->find('layout._playlist_size')) : ?>,
			size: <?php echo $size ?>
			<?php endif; ?>
		},
		<?php endif; ?>

		// options
		autostart: "<?php echo $autoplay ?>",

		// layout
		width: <?php echo is_numeric($width) ? $width : '"'.$width.'"' ?>,
		height: <?php echo is_numeric($height) ? $height : '"'.$height.'"' ?>
		<?php echo ($skin ? ",\n".'skin: "'.$skin.'"' : '') ?>
	});
	
});
</script>