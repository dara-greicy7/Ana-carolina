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

// show description only if it has content
if (!$this->category->description) {
	$this->params->set('template.show_description', 0);
}

// show image only if an image is selected
if (!($image = $this->category->getImage('content.image'))) {
	$this->params->set('template.show_image', 0);
}

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->category->alias; ?>">

	<?php if ($this->params->get('template.show_alpha_index')) : ?>
		<?php echo $this->partial('alphaindex'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('template.show_title') || $this->params->get('template.show_description') || $this->params->get('template.show_image')) : ?>

		<?php if ($this->params->get('template.show_title')) : ?>
		<h1><?php echo $this->category->name; ?></h1>
		<?php endif; ?>

		<?php if ($this->params->get('template.show_description') || $this->params->get('template.show_image')) : ?>
		<div class="uk-margin">
			<?php if ($this->params->get('template.show_image')) : ?>
			<img class="<?php echo 'uk-align-'.$this->params->get('template.alignment').($this->params->get('template.alignment') == "left" || $this->params->get('template.alignment') == "right" ? '@m' : ''); ?>" src="<?php echo $image['src']; ?>" title="<?php echo $this->category->name; ?>" alt="<?php echo $this->category->name; ?>" <?php echo $image['width_height']; ?>/>
			<?php endif; ?>
			<?php if ($this->params->get('template.show_description')) echo $this->category->getText($this->category->description); ?>
		</div>
		<?php endif; ?>

	<?php endif; ?>


	<?php

		echo '<div class="uk-margin">';

		// render categories
		$has_categories = false;
		if ($this->params->get('template.show_categories', true) && ($this->category->childrenHaveItems() || ($this->params->get('config.show_empty_categories', false) && !empty($this->selected_categories)))) {
			$has_categories = true;
			echo $this->partial('categories');
		}

		echo '</div>';

	?>

	<?php

		// render items
		if (count($this->items)) {
			$itemstitle = $this->category->getParams()->get('items_title', '');
			echo $this->partial('items', compact('itemstitle', 'has_categories'));
		}

	?>

</div>
