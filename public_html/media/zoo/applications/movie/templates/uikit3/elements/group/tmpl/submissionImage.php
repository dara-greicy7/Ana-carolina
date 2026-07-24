<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$this->app->document->addScript('elements:image/submission.js');

$image = $this->get($element['name']);
if (!empty($image)) {
    $image = $this->app->path->url('root:' . $image);
}

$identifier = uniqid('element');

?>

<div id="<?php echo $identifier; ?>" class="uk-clearfix">

    <div class="zo-upload">
        <input class="uk-input uk-form-width-large" type="text" id="filename<?php echo $identifier; ?>" readonly="readonly" />
        <div class="zo-button-container">
            <button class="uk-button uk-button-default search" type="button"><?php echo JText::_('Search'); ?></button>
            <input type="file" name="<?php echo $this->getControlName($element['name']); ?>" onchange="javascript: document.getElementById('filename<?php echo $identifier; ?>').value = this.value.replace(/^.*[\/\\]/g, '');" />
        </div>
    </div>

    <?php if ($params['trusted_mode']) : ?>

        <?php echo $this->getImageSelectList($element['name']); ?><span class="uk-form-help-inline"><?php echo JText::_('ALREADY UPLOADED'); ?></span>

    <?php else : ?>

        <input type="hidden" class="image" name="<?php echo $this->getControlName($element['name']); ?>" value="<?php echo $image ? 1 : ''; ?>">

    <?php endif; ?>

    <div class="uk-margin image-preview">
        <img src="<?php echo $image; ?>" alt="preview">
        <span class="image-cancel" title="<?php JText::_('Remove image'); ?>"><span class="uk-icon" uk-icon="icon: trash"></span></span>
    </div>

</div>

<script type="text/javascript">
    jQuery(function($) {
        $('#item-submission #<?php echo $identifier; ?>').ImageSubmission({ uri: '<?php echo JURI::root(); ?>' });
    });
</script>
