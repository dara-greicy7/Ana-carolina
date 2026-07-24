<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

// create label
$label = '';
if (isset($params['showlabel']) && $params['showlabel']) {
	$label .= '<strong>';
	$label .= ($params['altlabel']) ? $params['altlabel'] : $element->config->get('name');
	$label .= ': </strong> ';
}

// create class attribute
$class = 'uk-margin-remove element element-'.$element->getElementType();

?>

<li class="<?php echo $class; ?>">
	<?php echo $label.$element->render($params); ?>
</li>
