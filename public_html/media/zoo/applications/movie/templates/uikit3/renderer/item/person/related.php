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

<?php if ($this->checkPosition('media')) : ?>
<div class="uk-align-left@m">
	<?php echo $this->renderPosition('media'); ?>
</div>
<?php endif; ?>

<?php if ($this->checkPosition('title')) : ?>
<h4 class="uk-margin-remove-top">
	<?php echo $this->renderPosition('title'); ?>
</h4>
<?php endif;
