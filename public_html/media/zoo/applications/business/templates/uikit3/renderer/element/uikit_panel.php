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
<div class="uk-card uk-card-default uk-card-body <?php echo $class; ?>">
	<?php echo $label.$element->render($params); ?>
</div>
