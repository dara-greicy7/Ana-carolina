
<?php if ($this['config']->get('article_style')=='tm-article-blog') : $blog_overview = (bool) $url; ?>

<article class="uk-article tm-article <?php if (isset($is_column_item) && $is_column_item) echo 'tm-article-column-item'; ?>" <?php if ($permalink) echo 'data-permalink="'.$permalink.'"'; ?>>

	<?php if ($blog_overview) : ?>
	<!-- Blog Overview -->

		<?php if ($date) : ?>
		<div class="tm-article-date uk-text-center uk-align-left uk-visible-large">
		    <?php printf('<span class="tm-article-date-day">'.JHtml::_('date', $date, JText::_('d')).'</span>'.'<span class="tm-article-date-month">'.JHtml::_('date', $date, JText::_('m')).'</span>'); ?>
		</div>

		<div>
		<?php endif; ?>

		<?php if ($image) : ?>
		<div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-match data-uk-grid-margin>
			<div <?php echo $image_alignment == 'right' ? 'class="uk-flex-order-last-medium"' : '' ?>>
				<div class="uk-cover-background tm-featured-image uk-position-relative" style="background-image:url(<?php echo $image; ?>);">
					<?php if ($url) : ?>
						<a href="<?php echo $url; ?>" class="uk-position-cover"></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="tm-article-container <?php if (!$image) echo 'uk-flex uk-flex-column'; ?>">

			<?php if ($title || $author || $date || $category) : ?>
			<div class="tm-article-header">

				<?php if ($title) : ?>
				<h1 class="uk-article-title">
					<?php if ($url && $title_link) : ?>
						<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
					<?php else : ?>
						<?php echo $title; ?>
					<?php endif; ?>
				</h1>
				<?php endif; ?>

				<?php echo $hook_aftertitle; ?>

				<?php if ($author || $date || $category) : ?>
				<p class="uk-article-meta uk-margin-bottom-remove">

					<?php
						$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
						$date     = ($date) ? ($datetime ? '<time datetime="'.$datetime.'">'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
						$category = ($category && $category_url) ? '<a href="'.$category_url.'">'.$category.'</a>' : $category;

						if ($author) {
							printf(JText::_('TPL_WARP_META_AUTHOR'), $author);
						}
						if ($date) {
							echo ' ';
							echo '<span class="uk-hidden-large">';
							printf(JText::_('TPL_WARP_META_DATE'), $date);
							echo '</span>';
						}

						if ($category) {
							echo ' ';
							printf(JText::_('TPL_WARP_META_CATEGORY'), $category);
						}
					?>

				</p>
				<?php endif; ?>

			</div>
			<?php endif; ?>

			<?php echo $hook_beforearticle; ?>

			<?php if ($article) : ?>
				<?php echo $article; ?>
			<?php endif; ?>

			<?php if ($tags) : ?>
			<p><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
			<?php endif; ?>

			<?php if ($more) : ?>
			<p>
				<a class="uk-button uk-button-link uk-margin-top" href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $more; ?></a>
			</p>
			<?php endif; ?>

			<?php echo $hook_afterarticle; ?>

		</div>

		<?php if ($image) : ?>
		</div>
		<?php endif; ?>

		<?php if ($blog_overview && $date) : ?>
		</div>
		<?php endif; ?>

	<?php else: ?>
	<!-- Blog Single -->

		<?php if ($image) : ?>
		<div class="uk-cover-background tm-featured-image" style="background-image:url(<?php echo $image; ?>);"></div>
		<?php endif; ?>

		<?php if ($title) : ?>
		<h1 class="uk-article-title">
			<?php if ($url && $title_link) : ?>
				<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			<?php else : ?>
				<?php echo $title; ?>
			<?php endif; ?>
		</h1>
		<?php endif; ?>

		<?php echo $hook_aftertitle; ?>

		<?php if ($author || $date || $category) : ?>
		<p class="uk-article-meta">

			<?php

				$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
				$date     = ($date) ? ($datetime ? '<time datetime="'.$datetime.'">'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
				$category = ($category && $category_url) ? '<a href="'.$category_url.'">'.$category.'</a>' : $category;

				if($author && $date) {
					printf(JText::_('TPL_WARP_META_AUTHOR_DATE'), $author, $date);
				} elseif ($author) {
					printf(JText::_('TPL_WARP_META_AUTHOR'), $author);
				} elseif ($date) {
					printf(JText::_('TPL_WARP_META_DATE'), $date);
				}

				if ($category) {
					echo ' ';
					printf(JText::_('TPL_WARP_META_CATEGORY'), $category);
				}

			?>

		</p>
		<?php endif; ?>

		<?php echo $hook_beforearticle; ?>

		<?php if ($article) : ?>
			<?php echo $article; ?>
		<?php endif; ?>

		<?php if ($tags) : ?>
		<p><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
		<?php endif; ?>

		<?php if ($edit) : ?>
		<p><?php echo $edit; ?></p>
		<?php endif; ?>

		<?php if ($this['config']->get('article_meta', false) && ($date_published || $date_modified || $hits)) : ?>
		<div class="uk-article-meta">
			<?php
				$date_published = ($date_published) ? JHtml::_('date', $date_published, JText::_('DATE_FORMAT_LC3')) : '';
				$date_modified = ($date_modified) ? JHtml::_('date', $date_modified, JText::_('DATE_FORMAT_LC3')) : '';
			?>
			<ul class="uk-list">
				<?php if ($date_published) : ?>
					<li><?php printf(JText::_('COM_CONTENT_PUBLISHED_DATE_ON'), $date_published); ?></li>
				<?php endif; ?>

				<?php if ($date_modified) : ?>
					<li><?php printf(JText::_('COM_CONTENT_LAST_UPDATED'), $date_modified); ?></li>
				<?php endif; ?>

				<?php if ($hits) : ?>
					<li><?php printf(JText::_('COM_CONTENT_ARTICLE_HITS'), $hits); ?></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>

		<?php
		if ($previous || $next) : ?>
		<ul class="uk-pagination">
			<?php if ($previous) : ?>
			<li class="uk-pagination-previous">
				<a href="<?php echo $previous; ?>"><i class="uk-icon-angle-double-left"></i> <?php echo JText::_('JPREV'); ?></a>
			</li>
			<?php endif; ?>

			<?php if ($next) : ?>
			<li class="uk-pagination-next">
				<a href="<?php echo $next; ?>"><?php echo JText::_('JNEXT'); ?> <i class="uk-icon-angle-double-right"></i></a>
			</li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>

		<?php echo $hook_afterarticle; ?>

	<?php endif; ?>

</article>

<?php else: ?>

	<?php include(__DIR__.'/../warp/systems/joomla/layouts/article.php'); ?>

<?php endif; ?>
