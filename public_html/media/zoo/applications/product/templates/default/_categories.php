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

<div class="categories <?php if ($categoriestitle) echo 'has-box-title'; ?>">

	<?php if ($categoriestitle) : ?>
	<h1 class="box-title"><span><span><?php echo $categoriestitle; ?></span></span></h1>
	<?php endif; ?>

	<div class="box-t1">
		<div class="box-t2">
			<div class="box-t3"></div>
		</div>
	</div>

	<div class="box-1">
		<?php

			// init vars
			$i = 0;
			$columns = $this->params->get('template.categories_cols', 2);

			// render rows
            foreach ($this->selected_categories as $key => $category) {
				if ($category && !($category->totalItemCount() || $this->params->get('config.show_empty_categories', false))) continue;
				if ($i % $columns == 0) echo ($i > 0 ? '</div><div class="row">' : '<div class="row first-row">');
				$firstcell = ($i % $columns == 0) ? 'first-cell' : null;
				echo '<div class="width'.intval(100 / $columns).' '.$firstcell.'">'.$this->partial('category', compact('category')).'</div>';
				$i++;
			}
			if (!empty($this->selected_categories)) {
				echo '</div>';
			}

		?>
	</div>

	<div class="box-b1">
		<div class="box-b2">
			<div class="box-b3"></div>
		</div>
	</div>

</div>