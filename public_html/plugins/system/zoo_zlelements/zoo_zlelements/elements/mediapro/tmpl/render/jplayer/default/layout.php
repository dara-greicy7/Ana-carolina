<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// init vars
$swfpath	= $this->app->path->url('elements:mediapro/assets/players/jplayer');
$autoplay 	= $params->find('layout._autoplay');
$loop		= $params->find('layout._loop');
$screen		= $params->find('layout._screen_display', 1);
$skin		= $params->find('layout._skin', 'pink.flag');

// prepare skin css
$skin = 'jp-skin-'.str_replace('.', '-', $skin);

?>
<div class="<?php echo $skin ?>">
	<div id="jp_container_<?php echo $id ?>" class="jp-video jp-container">
		<div class="jp-type-<?php echo $params->find('layout._playlist', 0) ? 'playlist' : 'single' ?>">
			
			<div id="jquery_jplayer_<?php echo $id ?>" class="jp-jplayer"></div>
			
			<div class="jp-gui">
				<div class="jp-video-play">
					<a href="javascript:;" class="jp-video-play-icon" tabindex="1"><?php echo JText::_('PLG_ZLELEMENTS_MP_PLAY') ?></a>
				</div>
				<div class="jp-interface">
					<div class="jp-title">
						<ul>
							<li></li>
						</ul>
					</div>
					<div class="jp-col jp-col-volume"><div class="jp-col-wrapper">
						<div class="jp-volume-controls">
							<a href="javascript:;" class="jp-mute" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_MUTE') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_MUTE') ?>
							</a>
							<a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_UNMUTE') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_UNMUTE') ?>
							</a>
							<a href="javascript:;" class="jp-volume-max" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_MAX_VOLUME') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_MAX_VOLUME') ?>
							</a>
							<div class="jp-volume-bar">
								<div class="jp-volume-bar-value"></div>
							</div>
						</div>
					</div></div>
					<div class="jp-col jp-col-controls"><div class="jp-col-wrapper">
						<ul class="jp-controls">
							<?php if ($params->find('layout._playlist', 0)) : ?>
							<li><a href="javascript:;" class="jp-previous" tabindex="1">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_PREVIOUS') ?>
							</a></li>
							<?php endif; ?>
							<li><a href="javascript:;" class="jp-play" tabindex="1">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_PLAY') ?>
							</a></li>
							<li><a href="javascript:;" class="jp-pause" tabindex="1">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_PAUSE') ?>
							</a></li>
							<?php if ($params->find('layout._playlist', 0)) : ?>
							<li><a href="javascript:;" class="jp-next" tabindex="1">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_NEXT') ?>
							</a></li>
							<?php endif; ?>
							<li><a href="javascript:;" class="jp-stop" tabindex="1">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_STOP') ?>
							</a></li>
						</ul>
					</div></div>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>

					<?php if (!$params->find('layout._playlist')) : ?>
					<div class="jp-title">
						<ul>
							<li><?php echo $playlist[0]['title'] ?></li>
						</ul>
					</div>
					<?php endif; ?>

					<div class="jp-col jp-col-toggles"><div class="jp-col-wrapper">
						<ul class="jp-toggles">
							<li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_FULL_SCREEN') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_FULL_SCREEN') ?>
							</a></li>
							<li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_RESTORE_SCREEN') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_RESTORE_SCREEN') ?>
							</a></li>

							<?php if ($params->find('layout._playlist', 0)) : ?>
							<li><a href="#" class="jp-shuffle" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_SHUFFLE') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_SHUFFLE') ?>
							</a></li>
							<li><a href="#" class="jp-shuffle-off" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_SHUFFLE_OFF') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_SHUFFLE_OFF') ?>
							</a></li>
							<?php endif; ?>

							<li><a href="#" class="jp-repeat" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_REPEAT') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_REPEAT') ?>
							</a></li>
							<li><a href="#" class="jp-repeat-off" tabindex="1" title="<?php echo JText::_('PLG_ZLELEMENTS_MP_REPEAT_OFF') ?>">
								<?php echo JText::_('PLG_ZLELEMENTS_MP_REPEAT_OFF') ?>
							</a></li>
						</ul>
					</div></div>
				</div>
			</div>
			<div class="jp-playlist">
				<ul>
					<!-- The method Playlist.displayPlaylist() uses this unordered list -->
					<li></li>
				</ul>
			</div>
			<div class="jp-no-solution">
				<span><?php echo JText::_('PLG_ZLELEMENTS_MP_UPDATE_REQUIRED') ?></span>
				<?php echo JText::_('PLG_ZLELEMENTS_MP_UPDATE_REQUIRED_MSG') ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(function($){

	$('#jp_container_<?php echo $id; ?>').MediaProJPlayer({
		id: '<?php echo $id; ?>',
		playlist: <?php echo json_encode($playlist) ?>,
		playlist_on: false,
		supplied: '<?php echo implode(', ', $supplied) ?>',
		swfpath: '<?php echo $swfpath ?>',
		width: '<?php echo $width ?>',
		height: '<?php echo $height ?>',
		autoplay: '<?php echo $autoplay ?>',
		loop: '<?php echo $loop ?>',
		screenmode: <?php echo $screen ?>
	});

});
</script>