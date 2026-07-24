<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

/*
	Class: ElementImagePro
		The image element class
*/
class ElementImagePro extends ElementFilesPro implements iRepeatSubmittable {

	protected $_extensions = 'bmp|gif|jpg|jpeg|png';

	/*
	   Function: Constructor
	*/
	public function __construct() {

		// call parent constructor
		parent::__construct();

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
		$event['html'] = str_replace('class="element element-imagepro', 'class="element element-image element-imagepro', $event['html']);
	}
	
	/*
		Function: getSearchData
			Get elements search data.
					
		Returns:
			String - Search data
	*/
	public function _getSearchData() {
		if ($this->config->find('specific._custom_options') && $this->config->find('specific._custom_title')) {
			return $this->get('title');
		}		
		return null;
	}
	
	/*
		Function: getPreview
			Return file preview
			
		Returns:
			HTML
	*/
	public function getPreview($file = null)
	{
		$sourcepath = $this->app->path->path('root:'.$file);
		if (is_file($sourcepath)){
			return '<img src="'.$this->app->path->url('root:'.$file).'">';
		} else if (is_dir($sourcepath)){
			return '<img src="'.$this->app->path->url('elements:filespro/assets/images/folder_horiz.png').'">';
		}
	}

	/*
		Function: getRenderedValues
			render repeatable values

		Returns:
			array
	*/
	public function getRenderedValues($params=array(), $wk=false, $opts=array())
	{
		$result = parent::getRenderedValues($params, $wk, $opts);
		
		if (empty($result)) return null; // if no results abort

		// perform the _sublayout if is not WidgetKit
		if($wk == false) foreach ($result['result'] as $key => $image)
		{
			$layout = JFile::stripExt($params->find('layout._layout', 'default.php'));
			if($layout = $this->getLayout('render/' .$layout . '/_sublayouts/' . $params->find('layout._sublayout', '_default.php'))){
				$result['result'][$key] = $this->renderLayout($layout, array('img' => $image, 'params' => $params));
			}
		}
		return $result;
	}
	
	/*
		Function: render
			Renders the repeatable element.

	   Parameters:
			$params - AppData render parameter

		Returns:
			String - html
	*/
	protected function _render($params=array(), $mode='', $opts=array())
	{	
		// init vars
		$opts 		  = $this->app->data->create($opts); // overide options
		$width 		  = $opts->get('width', $params->find('specific._width', 0));
		$height		  = $opts->get('height', $params->find('specific._height', 0));
		$ac			  = $opts->get('avoid_cropping', $params->find('specific._avoid_cropping', 0));
		$file2		  = $this->get('file2', 0);
		$avoid_resize_small = $opts->get('avoid_resize_small', $params->find('specific._avoid_resize_small', 0));
		
		$result = array();
		
		if ($opts->get('file2') && $file2 && $sourcepath = $this->app->path->path('root:'.$file2)) // get file 2
		{
			if (is_file($sourcepath) && is_readable($sourcepath)){
				$result[] = $this->getImgObject($file2, $params, $width, $height, $ac, $avoid_resize_small);
			}
		}
		else foreach ($this->getFiles($this->get('file')) as $file) // get default file or files from folder
		{
			$result[] = $this->getImgObject($file, $params, $width, $height, $ac, $avoid_resize_small);
		}

		return $result;
	}
	
	/*
		Function: getImgObject
			Renders the repeatable element

		Returns:
			String - array
	*/
	protected function getImgObject($file=null, $params=array(), $width=0, $height=0, $ac=0, $avoid_resize_small=0)
	{
		$valid_files  = $this->getValidResources($file);
		$sourcefile	  = 'root:'.$this->app->path->relative($file);
		$ext		  = $this->getExtension($file);
		$file  		  = $this->app->zlfw->resizeImage(JPATH_ROOT.'/'.$file, $width, $height, $ac, $unique = null, $cache = true, $avoid_resize_small);
		$url  		  = JURI::base().$this->app->path->relative($file); // using base is important
		$info 		  = getimagesize($file);
		$title  	  = $this->get('title');

		$overlay_caption	= $this->get('caption');
		$real_file			= new FilesProSplFileInfo($sourcefile, $this);
		$filename			= $real_file->getFilename();

		$link = $target = $rel = '';
		if ($params->find('specific._link_to_item', false) && $this->getItem()->getState())
		{
			$link   = $this->app->route->item($this->_item, false);
			$title = empty($title) ? $this->_item->name : $title;
		}

		// override link from item
		if ($this->get('link'))
		{
			$link 	= $this->get('link');
			$target	= $this->get('target');
			$rel  	= $this->get('rel');
		}
		
		// get alt
		$alt = empty($title) ? $this->_item->name : $title;
		
		$relative = 'root:'.$this->app->path->relative($file);

		// override with the default if no title defined
		if (!$overlay_caption) {
			$overlay_caption = $this->app->string->str_ireplace(
				array('{filename}', '{title}', '{title|filename}'),
				array($filename, $title, ($title ?: $filename)),
				$params->find('layout._default_overlay_caption', '{title|filename}') ?: '{title|filename}'
			);
		}

		// lightbox caption
		$lightbox_caption = $this->app->string->str_ireplace(
			array('{filename}', '{title}', '{title|filename}'),
			array($filename, $title, ($title ?: $filename)),
			$params->find('layout._default_lightbox_caption', '{title|filename}')
		);

		$img = array();
		$img['sourcefile']			= $sourcefile;
		$img['sourceurl']			= $this->app->path->url($sourcefile);
		$img['file'] 				= $relative;
		$img['fileurl']				= $this->app->path->url($relative);
		$img['filename'] 			= basename($sourcefile, '.'.$ext);
		$img['title'] 				= htmlentities(JText::_($title));
		$img['caption']				= JText::_($overlay_caption);
		$img['lightbox_caption']	= $lightbox_caption;
		$img['alt'] 				= htmlentities(JText::_($alt));
		$img['ext'] 				= $ext;
		$img['link'] 				= (strpos($link, 'index.php') !== 0) ? htmlentities($link) : $link;
		$img['target'] 				= $target;
		$img['rel'] 				= $rel;
		$img['name'] 				= basename($sourcefile);
		$img['width'] 				= $info[0];
		$img['height'] 				= $info[1];
		$img['overlay_effect']		= $this->get('spotlight_effect', '') ?: $this->get('overlay_effect', '');
		$img['lightbox_image']		= $this->get('file2', '') ? $this->get('file2', '') : array_shift($valid_files);
		
		return $img;
	}
	
	/*
	   Function: _edit
		   Renders the repeatable edit form field.

	   Returns:
		   String - html
	*/
	public function _edit(){
		$sublayout = $this->config->find('specific._edit_sublayout', '_edit.php');
		if ($layout = $this->getLayout("edit/$sublayout")) {
			return $this->renderLayout($layout, array());
		}
	}
	
	/*
		Function: _validateSubmission
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
		$required = $params->get('required');
		$userfile = $value->get('userfile', null);
		$file 		  = '';

		if ($params->find('layout._layout') == 'advanced' && $trusted_mode) // advanced
		{
			$file 	= $this->app->validator->create('string', array('required' => $required))->clean($value->find('values.file'));
			$title 	= $this->app->validator->create('string', array('required' => false))->clean($value->find('values.title'));
			$rel 	= $this->app->validator->create('string', array('required' => false))->clean($value->find('values.rel'));
			$link 	= $this->app->validator->create('string', array('required' => false))->clean($value->find('values.link'));
			$target = $this->app->validator->create('string', array('required' => false))->clean($value->find('values.target'));

			// wk integration
			$file2 	= $this->app->validator->create('string', array('required' => false))->clean($value->find('values.file2'));
			$overlay_effect = $this->app->validator->create('string', array('required' => false))->clean($value->find('values.overlay_effect'));
			$caption = $this->app->validator->create('string', array('required' => false))->clean($value->find('values.caption'));

			/* start validation */
			try {
				$mode = $this->config->find('files._mode', 0);

				$fileName = $file;

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

							$userfile = $this->app->validator
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
			/* end validation */

			return compact('file', 'title', 'rel', 'link', 'target', 'overlay_effect', 'caption', 'file2');
		}
		else if ($value->find('values.image') != null && $old_file = $value->get('old_file')) // use old file
		{
			// get the correct old file as it has could been reordered
			$old_file = $old_file[$value->find('values.image')];

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

				//get legal mime types
				$legal_mime_types = $this->app->data->create(array_intersect_key($this->app->filesystem->getMimeMapping(), array_flip($extensions)))->flattenRecursive();

				$max_upload_size = $this->config->find('files._max_upload_size', '1024') * 1024;
				$max_upload_size = empty($max_upload_size) ? null : $max_upload_size;
				
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
			throw new AppValidatorException('Please select an image to upload.');
		}

		return compact('file');
	}

	/*
		Function: bindData
			Set data through data array.

		Parameters:
			$data - array

		Returns:
			Void
	*/
	public function bindData($data = array()) {

		// add image width/height
		foreach ($data as $index => $instance_data) {
			$file = @$instance_data['file'];
			$filepath = $this->app->path->path('root:'.$file);
			if ($file && $filepath && is_file($filepath)) {
				$size = getimagesize($filepath);
				$data[$index]['width'] = ($size ? $size[0] : 0);
				$data[$index]['height'] = ($size ? $size[1] : 0);
			} elseif (!$file) { //unset if empty image
				unset($data[$index]);
			}
		}

		parent::bindData($data);
	}
}
