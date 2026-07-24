<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="uk-grid" uk-grid>
	<div class="uk-width-1-1">

		<div class="uk-card uk-card-default uk-card-body">

			<?php

				// init vars
				$i = 0;
				$columns = $this->params->get('template.items_cols', 2);
				reset($this->items);

				// render rows
                foreach ($this->items as $key => $item) {
					if ($i % $columns == 0) echo ($i > 0 ? '</div><div class="uk-grid" uk-grid uk-match-height>' : '<div class="uk-grid" uk-grid uk-match-height>');
					echo '<div class="uk-width-1-'.$columns.'@m">'.$this->partial('item', compact('item')).'</div>';
					$i++;
				}
				if (!empty($this->items)) {
					echo '</div>';
				}

			?>

		</div>

	</div>
</div>

<?php echo $this->partial('pagination'); ?>
