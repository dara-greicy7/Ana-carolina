<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

$this->app->document->addStyleDeclaration('
	.btn-toolbar {
		font-size: inherit;
	}
');

// load assets
$this->app->document->addStylesheet('zlmedia:css/theme.css');
$this->app->document->addStylesheet('zlmedia:apps/manager/manager.css');

$this->app->document->addScript('zlmedia:vendor/uikit/js/uikit.min.js');
$this->app->document->addScript('zlmedia:vendor/uikit/js/components/tooltip.min.js');
// $this->app->document->addScript('zlmedia:vendor/moment/min/moment.min.js');
$this->app->document->addScript('zlmedia:vendor/vue/dist/vue.min.js');
$this->app->document->addScript('zlmedia:vendor/vuex/dist/vuex.min.js');
$this->app->document->addScript('zlmedia:vendor/vue-resource/dist/vue-resource.min.js');
$this->app->document->addScript('zlmedia:vendor/vue-validator/dist/vue-validator.min.js');
$this->app->document->addScript('zlmedia:apps/manager/manager.js');

// add page title
JToolbarHelper::title('ZOOlanders::' . JText::_('Extensions'), '48-zoolanders');

// set auth
$auth = $this->app->zl->getConfig('admin-auth');
if ($auth->get('username') && $auth->get('password') && $auth->get('user')) {
	$auth = array(
		'status' => true,
		'user' => $auth->user
	);
}

// set server status
$server = OPENSSL_VERSION_NUMBER > 0x1000003f
	? array('offline' => false)
	: array(
		'offline' => true,
		'status' => 'The connection to zoolanders.com failed because the OpenSSL
			v0.9.8 installed in your server<br />is considered obsolete. For more
			information please review the <a href="http://docs.zoolanders.com/faq#commonFAQ">F.A.Q</a>
			or contact our support.'
	);

?>

<div id="vue-init">
	<admin-manager :state="$data"></admin-manager>
</div>

<script>
// add style class to toolbar
jQuery('#toolbar').addClass('zx')
// prepare vue-resource
Vue.url.options.root = '<?php echo trim(\JURI::base(), '/') ?>',
Vue.http.interceptors.push({
	request: function (options) {
		if (typeof options.url === 'string' && !options.url.match(/^(https?:)?\//)) {
			var parts = options.url.split('/');
			options.params = Vue.util.extend(options.params, {
				option: 'com_zoolanders',
				format: 'raw',
				controller: parts[0],
				task: parts[1]
			});
			options.params['<?php echo \JFactory::getSession()->getFormToken() ?>'] = 1
			options.url = 'index.php'
		}
		return options;
	}
})
// init Vue
new Vue({
	el: '#vue-init',
	data: {
		products: {
			authentication: <?php echo json_encode($auth) ?>,
			server: <?php echo json_encode($server) ?>
		},
		menu: <?php echo $this->app->zl->menu->get('nav')->renderJSON() ?>
	}
});
</script>
