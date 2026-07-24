<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

class JFormFieldZooApplication extends JFormField {

	protected $type = 'ZooApplication';

	public function getInput() {

		// load config
		require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

		return App::getInstance('zoo')->field->render('zooapplication', $this->fieldname, $this->value, $this->element, array('control_name' => "jform[{$this->group}]", 'parent' => $this->form->getValue('params')));
	}

}