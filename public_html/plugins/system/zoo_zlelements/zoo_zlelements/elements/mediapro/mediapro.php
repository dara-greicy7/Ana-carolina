<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/*
	Class: ElementMedia
		The video element class
*/
class ElementMediaPro extends ElementFilesPro implements iRepeatSubmittable {

	protected $_extensions = 'mp3|mp4|ogg|webm|wav|flv|swf|wmv|m4v|ogv';
	protected $_youtube_regex = '/(\/\/.*?youtube\.[a-z]+)\/watch\?v=([^&]+)&?(.*)/';
	protected $_youtubeshort_regex = '/(\/\/.*?youtu\.be)\/([^\?]+)(.*)/i';
	protected $_vimeo_regex = '/(\/\/.*?)vimeo\.[a-z]+\/([0-9]+).*?/';
	protected $_media_count = 1;

	/*
		Function: get
			Gets the elements data.
			Media Pro drops the URL field in favor of a unique media field, to keep backward
			compatiblitiy the URL value will be checked.

		Returns:
			Mixed - the elements data
	*/
	public function get($key, $default = null)
	{
		$value = parent::get($key, $default);

		// if file empty check also url
		if ($key == 'file' && empty($value)) {
			return parent::get('url', $default);
		}

		// else go by default
		return $value;
	}

	/*
		Function: getLegalExtensionsArray
			Returns the allowed file extensions

		Returns:
			Array - the list of legal extensions
	*/
	public function getLegalExtensionsArray()
	{
		return array_map(create_function('$ext', 'return strtolower(trim($ext));'), explode('|', $this->config->find('files._extensions', $this->_extensions)));
	}

	/*
		Function: getVideoFormat
			Trys to return the video format for source.

	   Parameters:
			$source - the video source

		Returns:
			String - the video format, if found
	*/
	public function getVideoFormat($source)
	{
		if (preg_match($this->_youtube_regex, $source)) {
			return 'youtube';
		} else if (preg_match($this->_youtubeshort_regex, $source)) {
			return 'youtu.be';
		} else if (preg_match($this->_vimeo_regex, $source)) {
			return 'vimeo';
		} else if (($ext = $this->app->zlfw->filesystem->getExtension($source)) && in_array($ext, $this->getLegalExtensionsArray())) {
			return strtolower($ext);
		}

		return null;
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
		Function: render
			Renders the element.

	   Parameters:
			$params - AppData render parameter

		Returns:
			String - html
	*/
	public function render($params = array())
	{
		// init vars
		$params = $this->app->data->create($params);
		$player = $this->config->find('specific._player');
		$layout = $params->find('layout._layout', 'default.php');

		// render layout with player path
		if ($layout = $this->getLayout("render/$player/$layout")) {
			return $this->renderLayout($layout, compact('params', 'player'));
		}
	}

	/*
		Function: _render
			Renders the element.

	   Parameters:
			$params - AppData render parameter

		Returns:
			String - html
	*/
	public function _render($params = array())
	{
		$result = array();
		foreach ($this->getFiles($this->get('file')) as $source) // get all files or default if set
		{
			$result[] = new FilesProSplFileInfo($source, $this);
		}

		return $result;
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

	/**
	 * Render the embed code from providers
	 *
	 * @param  object $params The element params
	 * @param  array $values The values to render
	 *
	 * @return array The embed code
	 *
	 * @since 3.0
	 */
	public function renderEmbedURL($width, $height, $url)
	{
		switch ($this->getVideoFormat($url)) {
			case 'vimeo':
				$url = preg_replace($this->_vimeo_regex, '//player.vimeo.com/video/$2', $url);
				$attrs = array('webkitallowfullscreen', 'mozallowfullscreen', 'allowfullscreen');
				break;

			case 'youtube':
				$url = rtrim(preg_replace($this->_youtube_regex, '//www.youtube.com/embed/$2?$3', $url), '?');
				$attrs = array('allowfullscreen');
				break;

			case 'youtu.be':
				$url = rtrim(preg_replace($this->_youtubeshort_regex, '//www.youtube.com/embed/$2$3', $url), '?');
				$attrs = array('allowfullscreen');
				break;
		}

		// set responsiveness
		$this->app->document->addScriptDeclaration(
			"jQuery(function($) {
				$.onMediaQuery('(max-width: 767px)', {
					valid: function() {
						$('iframe.zl-element-mediapro').attr('width', 'auto').attr('height', 'auto');
					},
					invalid: function() {
						$('iframe.zl-element-mediapro').attr('width', '$width').attr('height', '$height');
					}
				});
			});"
		);

		// render
		return '<iframe class="zl-element-mediapro" src="'.$url.'" width="'.$width.'" height="'.$height.'" '.implode(' ', $attrs).'></iframe>';
	}

	/**
	 * Get the cloudfront singned url
	 *
	 * @return string The image url
	 *
	 * @since 3.0
	 */
	function getCFSignedUrl($params, $path, $expiration = 300)
	{
		// init vars
		$key_pair = $params->find('layout._key_pair_id', '');
		$private_key = $params->find('layout._private_key', ''); // the private key file path

		$this->storage()->getAdapter()->s3->setSigningKey($key_pair, $private_key);
		$url = $this->storage()->getAdapter()->s3->getSignedCannedURL($path, $expiration);
		$this->storage()->getAdapter()->s3->freeSigningKey();

		return $url;
	}

	/**
	 * Get the media preview image
	 *
	 * @return string The image url
	 *
	 * @since 3.0
	 */
	function getPreviewImg($params)
	{
		$image = $url = null;
		if ($this->get('image')) {
			$image = $this->get('image');
		} else if ($params->find('layout._preview_img') == 1) {
			$element = $params->find('layout._preview_img_element');
			$image = $this->getItem()->getElement($element)->get('file');
		} else if ($params->find('layout._preview_img') == 2) {
			$image = $params->find('layout._preview_img_file');
		}

		// if valid image
		if ($image) {

			// if is not absolute create relative url
			if (strpos($image, 'http') !== 0) {
				$url = $this->app->path->url('root:'.$image);
				if (!$image = $this->app->path->path('root:'.$image)) return false;
			} else {
				$url = $image;
			}

			// get image parameters
			list($width, $height, $type, $attr) = getimagesize($image);

			// save all data
			$image = compact('url', 'width', 'height', 'type', 'attr');
		}

		return $this->app->data->create($image);
	}

	// time outsite panel
	protected function _toTimeString($n){
		$time_str = "";
		if ($n >= 3600 && true){   // Longer than one hour
			$hours = floor($n / 3600);
			$time_str .= floor($n / 3600) . ":";
			$n -= 3600 * $hours;
		}

		$time_str .= $this->_pad(floor($n / 60), 2) . ":" . $this->_pad(floor($n % 60), 2);
		return $time_str;
	}

	protected function _pad($n, $padLength){
		$str = (string)$n;
		while ( strlen($str) < $padLength )
		{
			$str = "0" . $str;
		}
		return $str;
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
				if (!empty($fileName) && !$this->getVideoFormat($fileName)) {

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
							$legal_mime_types = $this->app->data->create(array_intersect_key($this->app->filesystem->getMimeMapping(), array_flip($extensions)))->flattenRecursive();

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

				// get the uploaded file information


				// get legal extensions
				$extensions = $this->getLegalExtensionsArray();

				//get legal mime types
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

			if ($file && (!$format = $this->getVideoFormat($file))) {
				throw new AppValidatorException('Not a valid video format.');
			}
		}

		if ($params->get('required') && empty($file)) {
			throw new AppValidatorException('Please select a file to upload.');
		}

		$width     = (string) $this->app->validator->create('integer', array('required' => false), array('number' => 'The Width needs to be a number.'))->clean($value->find('values.width'));
		$height    = (string) $this->app->validator->create('integer', array('required' => false), array('number' => 'The Height needs to be a number.'))->clean($value->find('values.height'));
		$autoplay  = (bool) $value->find('values.autoplay');
		$image	   = $this->app->validator->create('string', array('required' => false))->clean($value->find('values.image'));
		$title	   = $this->app->validator->create('string', array('required' => false))->clean($value->find('values.title'));

		return compact('file', 'format', 'width', 'height', 'autoplay', 'image', 'title');
	}
}