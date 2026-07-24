<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// add additional stylesheet
$this->app->document->addStylesheet('assets:css/uikit3-zoo.css');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-tag'; ?>">

	<?php if ($this->params->get('template.show_alpha_index')) : ?>
		<?php echo $this->partial('alphaindex'); ?>
	<?php endif; ?>

	<?php

		// render items
		if (count($this->items)) {
			$itemstitle = JText::_('Items tagged with').' '.strtoupper($this->tag);
			echo $this->partial('items', compact('itemstitle'));
		}

	?>

</div>
