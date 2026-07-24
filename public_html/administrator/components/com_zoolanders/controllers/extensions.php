<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/**
 * The Extensions Controller Class
 */
class ExtensionsController extends AppController
{
	/**
	 * Retrieve extensions updated list
	 */
	public function retrieve()
	{
		$response = $this->app->zl->response->create();
		$force    = $this->app->request->get('force', 'bool', false);

		// get main extensions list
		$extensions = $this->app->zl->extensions->getExtensions();

		// retrieve updates from server
		if (!$retrieved = $this->app->zl->extensions->retrieve($force)) {
			$response->set('server', array(
				'offline' => true
			));
		} else {
			// merge if successful
			$extensions = $this->app->zl->extensions->
				mergeLists($extensions, $retrieved, 'name');

			foreach ($extensions as &$ext) {
				// set outdated state
				if ($ext['installed'] && isset($ext['version'])) {
					$ext['outdated'] = (bool) version_compare(
						$ext['version'],
						$ext['installedVersion']
					);
				}

				// fix type
				if ($ext['name'] === 'zoolanders') {
					$ext['type'] = 'Core';
				} else if ($ext['name'] === 'zooitempro') {
					$ext['type'] = 'Module';
				} else if ($ext['type'] === 'Extension') {
					$ext['type'] = 'Plugin';
				}
			}
		}

		$response->set('extensions', $extensions)->send();
	}

	/**
	 * Download extension package
	 */
	public function download()
	{
		$response   = $this->app->zl->response->create(403);
		$extensions = $this->app->request->get('extensions', 'array', array());

		if ($download = $this->app->zl->extensions->download($extensions)) {
			$response->set('downloadUrl', 'index.php?' . http_build_query(array(
				'option' => 'com_zoolanders',
				'controller' => 'zoolanders',
				'task' => 'outputFile',
				'format' => 'raw',
				'file' => basename($download)
			)))->code = 200;
		} else {
			$response->set('message', $this->app->zl->extensions->getError());
		}

		$response->send();
	}

	/**
	 * Download and install extension
	 */
	public function install()
	{
		$response   = $this->app->zl->response->create();
		$extensions = $this->app->request->get('extensions', 'array', array());

		if ($result = $this->app->zl->extensions->downloadAndInstall($extensions)) {
			$retrieved = $this->app->zl->extensions->retrieve();
			$installed = $this->app->zl->extensions->getInstalled();
			$extensions = $this->app->zl->extensions->mergeLists($installed, $retrieved, 'name');
			// set outdated state
			foreach ($extensions as &$ext) {
				if ($ext['installed'] && isset($ext['version'])) {
					$ext['outdated'] = (bool) version_compare(
						$ext['version'],
						$ext['installedVersion']
					);
				}
			}
			$response->data = $extensions;
		} else {
			$response->set('message', $this->app->zl->extensions->getError())->code = 500;
		}

		$response->send();
	}

	/**
	 * Uninstall extension
	 */
	public function uninstall()
	{
		$response   = $this->app->zl->response->create();
		$extensions = $this->app->request->get('extensions', 'array', array());

		if (!$result = $this->app->zl->extensions->uninstall($extensions)) {
			$response->set('message', $this->app->zl->extensions->getError())->code = 500;
		}

		$response->send();
	}

	/**
	 * Toggle Extension status
	 */
	public function toggle()
	{
		$response   = $this->app->zl->response->create();
		$extensions = $this->app->request->get('extensions', 'array', array());

		if ($result = $this->app->zl->extensions->toggle($extensions)) {
			$response->data = $this->app->zl->extensions->getInstalled();
		} else {
			$response->set('message', $this->app->zl->extensions->getError())->code = 500;
		}

		$response->send();
	}

	/**
	 * Enable Extension
	 */
	public function enable()
	{
		$response   = $this->app->zl->response->create();
		$extensions = $this->app->request->get('extensions', 'array', array());

		if ($result = $this->app->zl->extensions->enable($extensions)) {
			$response->data = $this->app->zl->extensions->getInstalled();
		} else {
			$response->set('message', $this->app->zl->extensions->getError())->code = 500;
		}

		$response->send();
	}

	/**
	 * Disable Extension
	 */
	 public function disable()
 	{
 		$response   = $this->app->zl->response->create();
 		$extensions = $this->app->request->get('extensions', 'array', array());

 		if ($result = $this->app->zl->extensions->disable($extensions)) {
 			$response->data = $this->app->zl->extensions->getInstalled();
 		} else {
 			$response->set('message', $this->app->zl->extensions->getError())->code = 500;
 		}

 		$response->send();
 	}
}
