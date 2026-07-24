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

<div>
    <div id="<?php echo $this->identifier ?>"class="row">
        <?php echo $this->app->html->_('control.text', $this->getControlName('location'), $this->get('location'), 'maxlength="255" title="'.JText::_('Location').'" placeholder="'.JText::_('Location').'"'); ?>
    </div>
</div>

<script type="text/javascript">
	jQuery(function($) {
      places({container: $('#<?php echo $this->identifier ?> input')[0]});
	});
</script>
