<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

// create label
$label = '';
if (isset($params['showlabel']) && $params['showlabel']) {
	$label .= '<h3>';
	$label .= ($params['altlabel']) ? $params['altlabel'] : $element->config->get('name');
	$label .= '</h3>';
}

// create class attribute
$class = 'element element-'.$element->getElementType();

?>

<?php echo $label; ?>

<ul class="uk-grid <?php echo $class; ?>" uk-grid>
	<?php echo $element->render($params); ?>
</ul>
