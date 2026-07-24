<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/**
 * The ZOOlanders Controller Class
 */
class ZoolandersController extends AppController
{
	public function __construct($default = array())
	{
		parent::__construct($default);

		// save the active comp ref
		$this->component = $this->app->component->active;

		$this->registerDefaultTask('extensions');
	}

	/**
	 * Extensions list display
	 *
	 * @return void
	 */
	public function extensions()
	{
		$this->getView()->setLayout('extensions')->display();
	}

	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{
		// set default task if none
		$task = $this->app->request->getWord('task', 'dashboard');

		// display view
		$this->getView()->setLayout($task)->display();
	}

	/**
	 * Log In user saving its credentials
	 *
	 * @return void
	 */
	public function login()
	{
		$username = $this->app->request->get('username', 'string', '');
		$password = $this->app->request->get('password', 'string', '');
		$response = $this->app->zl->response->create();

		$auth_response = $this->app->zl->remote->callServer('checkAuth', array(
			'controller' => 'zlmanager',
			'username' => urlencode($username),
			'password' => urlencode($password)
		));

		if ($auth_response->code === 200) {
			$password = $this->app->zlfw->crypt($password, 'encrypt');
			$user = $auth_response->user;
			$this->app->zl->setConfig('admin-auth', compact(
				'username', 'password', 'user'
			));
			$response->set('status', true);
			$response->set('user', $user);
		} else {
			$response
				->set('message', $auth_response->message)
				->set('status', false);
		}

		$response->send();
	}

	/**
	 * Log Out user deleting its saved credentials
	 *
	 * @return void
	 */
	public function logout()
	{
		$this->app->zl->setConfig('admin-auth', array());
		$this->app->zl->response->create()->send();
	}

	/**
	 * Output a file to the browser. Ajax request
	 */
	public function outputFile()
	{
		$response = $this->app->zl->response->create();
		$file = $this->app->path->path(
			'tmp:' . $this->app->request->get('file', 'string', null)
		);

		// check file
		if (!is_readable($file) && !is_file($file)) {
			$response
				->set('message', 'File does not exist or inaccessible')
				->code = 401;

			$response->send();
		}

		$this->app->filesystem->output($file);
	}

}
