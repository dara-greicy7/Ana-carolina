<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php if ($categoriestitle) : ?>
<h2><?php echo $categoriestitle; ?></h2>
<?php endif; ?>

<?php

	// init vars
	$i = 0;
	$columns = $this->params->get('template.categories_cols', 2);

	// render rows
	foreach ($this->selected_categories as $key => $category) {
		if ($category && !($category->totalItemCount() || $this->params->get('config.show_empty_categories', false))) continue;
		if ($i % $columns == 0) echo ($i > 0 ? '</div><hr class="uk-grid-divider">' : '') . '<div class="uk-grid uk-grid-divider" data-uk-grid-margin data-uk-grid-match>';
		echo '<div class="uk-width-small-1-2 uk-width-medium-1-'.$columns.'">'.$this->partial('category', compact('category')).'</div>';
		$i++;
	}
	if (!empty($this->selected_categories)) {
		echo '</div>';
	}

?>