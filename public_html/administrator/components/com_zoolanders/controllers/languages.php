<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/**
 * The Languages Controller Class
 */
class LanguagesController extends AppController
{
	/**
	 * Get languages list
	 */
	public function retrieve()
	{
		$response = $this->app->zl->response->create();
		$force    = $this->app->request->get('force', 'bool', false);

		// get main list
		$languages = $this->app->zl->transifex->getLanguages();

		// retrieve updates from server
		if (!$retrieved = $this->app->zl->transifex->retrieve($force)) {
			$response->set('server', array(
				'offline' => true
			));
		} else {
			// merge if successful
			$languages = $this->app->zl->transifex
				->mergeLists($languages, $retrieved, 'code');
		}

		$response->set('languages', $languages)->send();
	}

	/**
	 * Download language installer
	 */
	public function download()
	{
		$response  = $this->app->zl->response->create(403);
		$languages = $this->app->request->get('languages', 'array', array());

		if ($download = $this->app->zl->transifex->download($languages)) {
			$response->set('downloadUrl', 'index.php?' . http_build_query(array(
				'option' => 'com_zoolanders',
				'controller' => 'zoolanders',
				'task' => 'outputFile',
				'format' => 'raw',
				'file' => basename($download)
			)))->code = 200;
		} else {
			$response->set('message', $this->app->zl->transifex->getError());
		}

		$response->send();
	}

	/**
	 * Install language package
	 */
	public function install()
	{
		$response  = $this->app->zl->response->create();
		$languages = $this->app->request->get('languages', 'array', array());

		if ($download = $this->app->zl->transifex->downloadAndInstall($languages)) {
			$response->data = $this->app->zl->transifex->getInstalled();
		} else {
			$response->set('message', $this->app->zl->transifex->getError())->code = 500;
		}

		$response->send();
	}

	/**
	 * Uninstall extension
	 */
	public function uninstall()
	{
		$response  = $this->app->zl->response->create();
		$languages = $this->app->request->get('languages', 'array', array());

		if (!$result = $this->app->zl->transifex->uninstall($languages)) {
			$response->set('message', $this->app->zl->transifex->getError())->code = 500;
		}

		$response->send();
	}
}
