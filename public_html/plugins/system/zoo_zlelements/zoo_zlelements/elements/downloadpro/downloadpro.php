<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/*
	Class: ElementDownloadPro
		The file download element class
*/
class ElementDownloadPro extends ElementFilesPro implements iRepeatSubmittable {

	protected $_extensions = 'png|jpg|gif|bmp|doc|mp3|mov|avi|mpg|zip|rar|gz|pdf|ppt';

	/*
	   Function: Constructor
	*/
	public function __construct() {

		// call parent constructor
		parent::__construct();

		// set callbacks
		$this->registerCallback('download');
		$this->registerCallback('reset');

		// register events
		$this->app->event->dispatcher->connect('element:afterdisplay', array($this, 'afterDisplay'));
	}

	/*
		Function: afterDisplay
			Change the element layout after it has been displayed

		Parameters:
			$event - object
	*/
	public static function afterDisplay($event)
	{
		$event['html'] = str_replace('class="element element-downloadpro', 'class="element element-download element-downloadpro', $event['html']);
	}

	/*
		Function: getLink
			Gets the link to the download.

		Returns:
			String - link
	*/
	public function getLink($params = null, $file = null, $subindex = 0)
	{
		if($this->config->find('files._s3', 0))
		{
			$bucket = $this->config->find('files._s3bucket');
			return $this->_S3()->getAuthenticatedURL($bucket, $file, 3600);
		}
		else
		{
			// init vars
			$download_mode = $this->config->find('specific._download_mode');
			$file = empty($file) ? $this->get('file') : $file;

			// create download link
			$query = array('task' => 'callelement', 'format' => 'raw', 'item_id' => $this->_item->id, 'element' => $this->identifier, 'method' => 'download');

			if ($download_mode == 1) { // attachment mode
				$query['args[0]'] = (string)$this->index();
				return $this->app->link($query);

			} else if ($download_mode == 2) { // protected mode
				$query['args[0]'] = (string)$this->index().'.'.$subindex;
				$query['args[1]'] = $this->filecheck($file);
				return $this->app->link($query);

			} else {
				return $file;
			}
		}
	}

	/*
		Function: download
			Download the file.

		Params: @index num File index + subindex

		Returns:
			Binary - File data
	*/
	public function download($index = '0.0', $check = '')
	{
		// array index
		$index = explode('.', $index);

		// set the correct element index
		$this->seek($index[0]);
	
		// init vars
		$file = $this->get('file');
		$filepath = $this->app->path->path("root:$file");
		$download_mode = $this->config->find('specific._download_mode');

		// check limit
		if ($this->isDownloadLimitReached()) {
			header('Content-Type: text/html');
			echo JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_LIMIT_REACHED');
			return;
		}
		
		// trigger on download event
		$canDownload = true;
		$event = $this->app->event->dispatcher->notify($this->app->event->create($this, 'element:download', compact('check', 'canDownload')))->getParameters();

		// output file
		if($event['canDownload'])
		{
			// external source
			if (strpos($file, 'http') === 0)
			{
				// attachment mode
				if ($download_mode == 1) {
					$this->set('hits', $this->get('hits', 0) + 1);
					// $this->app->zlfilesystem->output($file); TODO

				// protected mode
				} else if ($download_mode == 2
					// check today and yesterday giving 48h valid link
					&& ($this->filecheck($file) == $check || $this->filecheck($file, time() - 86400) == $check)) {
					$this->set('hits', $this->get('hits', 0) + 1);
					// $this->app->zlfilesystem->output($file); TODO
				}
				
			}

			// if file
			else if(is_readable($filepath) && is_file($filepath))
			{
				// attachment mode
				if ($download_mode == 1) {
					$this->set('hits', $this->get('hits', 0) + 1);
					$this->app->filesystem->output($filepath);

				// protected mode
				} else if ($download_mode == 2
					// check today and yesterday giving 48h valid link
					&& ($this->filecheck() == $check || $this->filecheck(null, time() - 86400) == $check)) {
					$this->set('hits', $this->get('hits', 0) + 1);
					$this->app->filesystem->output($filepath);
				}
				
			}

			// if directory
			else if(is_readable($filepath) && is_dir($filepath))
			{
				$files = $this->getFiles($this->app->path->relative($filepath));
				$file  = $files[$index[1]];

				// attachment mode
				if ($download_mode == 1) {
					$this->set('hits', $this->get('hits', 0) + 1);
					$this->app->filesystem->output($file);

				// protected mode
				} else if ($download_mode == 2 && $this->filecheck($file) == $check) {
					$this->set('hits', $this->get('hits', 0) + 1);
					$this->app->filesystem->output($file);
				}

			// if invalid file
			} else {
				header('Content-Type: text/html');
				echo JText::_('PLG_ZLELEMENTS_DWP_INVALID_FILE');
			}
			
		} else {
			header('Content-Type: text/html');
			echo JText::_('PLG_ZLELEMENTS_DWP_DOWNLOAD_FORBIDDEN');
		}

		//save item
		$item_table = new AppTable($this->app, ZOO_TABLE_ITEM);
		$item_table->save($this->getItem());
	}

	/*
		Function: getSearchData
			Get elements search data.

		Returns:
			String - Search data
	*/
	public function getSearchData() {
		$hits = $this->get('hits', 0);
		return $hits;
	}

	/*
		Function: render
			Renders the repeatable element.

	   Parameters:
			$params - AppData render parameter

		Returns:
			String - html
	*/
	public function _render($params = array())
	{
		$result = array();
		
		foreach ($this->getFiles($this->get('file')) as $file_index => $source) // get all files or default if set
		{
			$result[] = array(
				'file' => new FilesProSplFileInfo($source, $this),
				'file_index' => $file_index,
				'index' => $this->index()
			);
		}

		return $result;
	}

	/*
	   Function: filecheck
		   Get the file check string.

	   Returns:
		   String - md5(file + secret + date)
	*/
	public function filecheck($file = null, $time = null) {
		$file = empty($file) ? $this->get('file') : $file;
		$time = $time ? $time : time();
		return md5($file.$this->app->system->config->get('secret').date('Y-m-d', $time));
	}

	/*
		Function: reset
			Renset the Download this

		Returns:
			String - html
	*/
	public function reset()
	{
		$this->set('hits', 0);

		//save item
		$item_table = new AppTable($this->app, ZOO_TABLE_ITEM);
		$item_table->save($this->getItem());

		return $this->edit();
	}

	/*
		Function: edit
			Renders the edit layout

		Returns:
			String - html
	*/
	public function edit()
	{
		// render layout
		if ($layout = $this->getLayout('edit/'.$this->config->find('layout._layout', 'default.php'))) {
			return $this->renderLayout($layout, compact('params'));
		}
	}

	/*
	   Function: _edit
		   Renders the repeatable edit form field.

	   Returns:
		   String - html
	*/
	protected function _edit()
	{
		// render layout
		$layout = basename($this->config->find('layout._layout', 'default.php'), '.php');
		$sublayout = $this->config->find('layout._sublayout', '_default.php');
		if ($layout = $this->getLayout("edit/$layout/_sublayouts/$sublayout")) {
			return $this->renderLayout($layout);
		}
	}

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - AppData submission parameters

		Returns:
			String - html
	*/
	public function renderSubmission($params = array())
	{
        $this->loadAssets();
    
        // init vars
		$trusted_mode = $params->get('trusted_mode');
		$layout		  = $params->find('layout._layout', 'default.php');

		if ($layout == 'advanced.php' && !$trusted_mode) {
			$layout = 'default.php';
		}

		$params->set('layout._layout', $layout);

		if ($layout = $this->getLayout("submission/$layout"))
		{
			return $this->renderLayout($layout, compact('params', 'trusted_mode'));
		}
	}

	/*
		Function: _renderSubmission
			Renders the element in submission.

		Parameters:
			$params - AppData submission parameters

		Returns:
			String - html
	*/
	public function _renderSubmission($params = array())
	{
		// render layout
		$layout = basename($params->find('layout._layout', 'default.php'), '.php');
		$sublayout = $this->config->find('layout._sublayout', '_default.php');
		if ($layout = $this->getLayout("submission/$layout/_sublayouts/$sublayout")) {
			return $this->renderLayout($layout);
		}
	}
	
	/*
		Function: validateSubmission
			Validates the submitted element

	   Parameters:
			$value  - AppData value
			$params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function _validateSubmission($value, $params)
	{
		// init vars
		$trusted_mode = $params->get('trusted_mode');
		$required	  = $params->get('required');
		$userfile 	  = $value->get('userfile', null);
		$layout		  = $params->find('layout._layout');
		$file 		  = '';
	

		if ($layout == 'advanced.php' && $trusted_mode) // advanced
		{
			/* validate files and folders */
			try {
				$mode = $this->config->find('files._mode', 0);

				$fileName = $value->find('values.file');

				// validate if not empty file/folder name
				if (!empty($fileName)) {

					$fileDetails = json_decode($this->getFileDetails($fileName));

					if (!$fileDetails) {
						throw new AppValidatorException(sprintf('This file or folder does not exist.'));
					} else {
						// check the element mode
						if ($fileDetails->type == 'folder' && $mode == 'files') {
							throw new AppValidatorException(sprintf('Uploaded file is not of a permitted type.'));
						} elseif ($fileDetails->type == 'file' && $mode == 'folders') {
							throw new AppValidatorException(sprintf('Folder is not correct.'));
						}

						// validate file
						if ($fileDetails->type == 'file') {
							$userfile = array('name' => $fileName, 'tmp_name' => $fileName, 'type' => $fileDetails->content_type, 'size' => $fileDetails->size->value);

							// get legal extensions
							$extensions = array_map(create_function('$ext', 'return strtolower(trim($ext));'), explode('|', $this->config->find('files._extensions', $this->_extensions)));

							// get legal mime types
							$legal_mime_types = $this->app->data->create(array_intersect_key($this->app->zlfw->filesystem->getMimeMapping(), array_flip($extensions)))->flattenRecursive();

							// get max upload size
							$max_upload_size = $this->config->find('files._max_upload_size', '1024') * 1024;
							$max_upload_size = empty($max_upload_size) ? null : $max_upload_size;

							$file = $this->app->validator
								->create('filepro', array('mime_types' => $legal_mime_types, 'max_size' => $max_upload_size))
								->addMessage('mime_types', 'Uploaded file is not of a permitted type.')
								->clean($userfile);
						}
					}
				}
			} catch (AppValidatorException $e) {
				if ($e->getCode() != UPLOAD_ERR_NO_FILE) {
					throw $e;
				}
			}

			$file = $this->app->validator->create('string', array('required' => $required))->clean($value->find('values.file'));
			
			if ($required && empty($file)) {
				throw new AppValidatorException(sprintf('Please select a file to upload.'));
			}
		}
		else if ($value->find('values.upload') != null && $old_file = $value->get('old_file')) // use old file
		{
			// get the correct old file as it has could been reordered
			$old_file = $old_file[$value->find('values.upload')];

			// clean the file path
			$file = $this->app->validator->create('string', array('required' => $required))->clean($old_file);
			
			if ($required && !JFile::exists($file)) {
				throw new AppValidatorException(sprintf('This file does not exist.'));
			}
		}
		else if (!isset($userfile['error'])) // default
		{
			try {
				// get legal extensions
				$extensions = array_map(create_function('$ext', 'return strtolower(trim($ext));'), explode('|', $this->config->find('files._extensions', $this->_extensions)));

				// get legal mime types
				$legal_mime_types = $this->app->data->create(array_intersect_key($this->app->zlfw->filesystem->getMimeMapping(), array_flip($extensions)))->flattenRecursive();

				// get max upload size
				$max_upload_size = $this->config->find('files._max_upload_size', '1024') * 1024;
				$max_upload_size = empty($max_upload_size) ? null : $max_upload_size;

				// get MIME type if file exist in temp dir
				if (file_exists($userfile['tmp_name'])) {
					if (function_exists('finfo_open')) {
						$finfo            = finfo_open(FILEINFO_MIME_TYPE);
						$userfile['type'] = finfo_file($finfo, $userfile['tmp_name']);
						finfo_close($finfo);
					} elseif (function_exists('mime_content_type')) {
						$userfile['type'] = mime_content_type($userfile['tmp_name']);
					}
				}

				// validate
				$file = $this->app->validator
						->create('filepro', array('mime_types' => $legal_mime_types, 'max_size' => $max_upload_size))
						->addMessage('mime_types', 'Uploaded file is not of a permitted type.')
						->clean($userfile);
						
				// init vars
				$ext 		= strtolower(JFile::getExt($file['name']));
				$basename 	= substr($file['name'], 0, strrpos($file['name'], '.'));
				$targetDir 	= JPATH_ROOT.'/'.$this->getDirectory();

				// construct filename
				$fileName = "{$basename}.{$ext}";

				// Make sure the fileName is unique
				if (JFile::exists("$targetDir/$fileName")) {
					$count = 1;
					while (JFile::exists("{$targetDir}/{$basename}_{$count}.{$ext}"))
						$count++;
				
					$fileName = "{$basename}_{$count}.{$ext}";
				}

				$file = $this->app->path->relative("$targetDir/$fileName");
				
			} catch (AppValidatorException $e) {

				if ($e->getCode() != UPLOAD_ERR_NO_FILE) {
					throw $e;
				}
			}
		}

		if ($params->get('required') && empty($file)) {
			throw new AppValidatorException('Please select a file to upload.');
		}

		$download_limit = (string) $this->app->validator
				->create('integer', array('required' => false), array('number' => 'The Download Limit needs to be a number.'))
				->clean($value->find('values.download_limit'));
		$title 	= $this->app->validator->create('string', array('required' => false))->clean($value->find('values.title'));

		return compact('file', 'download_limit', 'title');
	}

}
