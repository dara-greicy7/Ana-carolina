<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>


<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->submission->alias; ?>">

	<h1 class="uk-h1"><?php echo JText::_('My Submissions'); ?></h1>

	<p><?php echo sprintf(JText::_('Hi %s, here you can edit your submissions and add new submission.'), $this->user->name); ?></p>

	<?php

		echo $this->partial('mysubmissions');

	?>

</div>
