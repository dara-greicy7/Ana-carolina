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

<ul class="zoo-alphaindex uk-subnav uk-subnav-divider uk-flex-center">
	<?php
        foreach (explode("\n", $this->alpha_index->render()) as $char) {
			$item = str_replace(['<span', '</span>'], ['<a href="#"', '</a>'], $char, $count);

			if ($count > 0) {
				echo "<li class='uk-disabled'>{$item}</li>";
			}
			else {
            	echo "<li>{$item}</li>";
			}
        }
    ?>
</ul>

<hr>
