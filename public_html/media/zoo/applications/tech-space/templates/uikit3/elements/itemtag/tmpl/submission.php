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

<div id="tag-area">

    <input type="text" value="<?php echo implode(', ', $tags) ?>" placeholder="<?php echo JText::_('Add tag') ?>" />

    <?php if (count($most)) : ?>
    <div class="uk-margin tag-cloud"><?php echo JText::_('Choose from the most used tags') ?>:
        <?php foreach ($most as $i => $tag) : ?>
        <a title="<?php echo $tag->items . ' item' . ($tag->items != 1 ? 's' : '') ?>"><?php echo $tag->name ?></a><?= $i != count($most) - 1 ? ',' : '' ?>
        <?php endforeach ?>
    </div>
    <?php endif ?>

</div>

<script type="text/javascript">
    jQuery(function($) {
        var tagArea = $('#tag-area');

        tagArea.Tag({url: '<?php echo $link ?>', inputName: '<?php echo $this->getControlName('value', true) ?>'});

        $('.add-tag-button', tagArea).addClass('uk-button uk-button-default');
        $('.as-selection-item', tagArea).addClass('uk-label');
        tagArea.on('addItem', 'input[type="text"]', function(e, li) {
            $(li).addClass('uk-label');
        });
    });
</script>
