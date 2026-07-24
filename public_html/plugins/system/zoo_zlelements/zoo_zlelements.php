<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

// import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class plgSystemZoo_zlelements extends JPlugin {
	
	public $joomla;
	public $app;

	/**
	 * onAfterInitialise handler
	 *
	 * Adds ZOO event listeners
	 *
	 * @access	public
	 * @return null
	 */
	function onAfterInitialise()
	{
		// Get Joomla instances
		$this->joomla = JFactory::getApplication();
		$jlang = JFactory::getLanguage();
		
		// load default and current language
		$jlang->load('plg_system_zoo_zlelements', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('plg_system_zoo_zlelements', JPATH_ADMINISTRATOR, null, true);

		// check dependences
		if (!defined('ZLFW_DEPENDENCIES_CHECK_OK')){
			$this->checkDependencies();
			return; // abort
		}

		// Get the ZOO App instance
		$this->app = App::getInstance('zoo');
		
		// register plugin path
		if ($path = $this->app->path->path('root:plugins/system/zoo_zlelements/zoo_zlelements')) {
			$this->app->path->register($path, 'zlpath');
		}

		// register elements path
		if ($path = $this->app->path->path('zlpath:elements')) {
			$this->app->path->register($path, 'elements');
		}

		// register fields path
		if ($path = $this->app->path->path('zlpath:fields')) {
			$this->app->path->register($path, 'zlfields');
		}

		// register events
		if($this->joomla->isAdmin()) $this->app->event->dispatcher->connect('type:beforesave', array($this, 'beforeTypeSave'));
	}
	
	/*
	 * Type Functions
	 */
	public function editTypeDisplay($event) {
		$type = $event->getSubject();
		$html = '<div class="element element-name"><strong>ZOOlanders Elements - qTip Style</strong>';
		
			// qtip
			jimport('joomla.html.parameter.element');
			$this->app->loader->register('JElementQtip', 'zlfw:fields/type/qtip.php');
			$qtip = $this->app->object->create('JElementQtip');
			$html .= $qtip->fetchElement('qtip', $type->config->find('zl.qtip'), null, 'zl');
		
		$html .= '</div>';

		$event['html'] = str_ireplace('</fieldset>', $html.'</fieldset>', $event['html']);
	}
	
	public static function beforeTypeSave($event) {
		$type = $event->getSubject();
		$type->config->set('zl', JRequest::getVar('zl'));
	}
	
	/*
		Function: biRelate
			Triggered on item:saved event for RI Pro Bi-relation feature
	*/
	public function biRelate($event) {

		// init vars
		$item = $event->getSubject();
		$elements = $item->getElements();
		$db = $this->app->database;
		
		// birelate the items
		foreach($elements as $element){
			if($element->getElementType() == 'relateditemspro'){
			
				// filter the elements and get the relations
				$chosenElms = $element->config->find('application._chosenelms', array());
				$ritems = $element->getRelatedItems(false); // get's even unpublsihed items
				
				// associate actual $item to it's relations
				if (!empty($ritems) && !empty($chosenElms)) foreach ($ritems as $ritem){
					foreach ($chosenElms as $relement_id) {
						if ($relement = $ritem->getElement($relement_id)) {
							// bi-related element found on item
							$relement->addRelation($item->id);
						}
					}					
				}
				
				// in same step search items marked to be Removed and remove
				foreach ($chosenElms as $relement_id) {
					$query = 'DELETE FROM #__zoo_relateditemsproxref'
							.' WHERE remove = 1' 
							.' AND ritem_id = '.$item->id
							.' AND element_id='.$db->Quote($relement_id);
					$db->query($query);
				}
			}
		}
		
		return null;
	}
	
	/*
	 *  checkDependencies
	 */
	public function checkDependencies()
	{
		if($this->joomla->isAdmin())
		{
			// if ZLFW not enabled
			if(!JPluginHelper::isEnabled('system', 'zlframework') || !JComponentHelper::getComponent('com_zoo', true)->enabled) {
				$this->joomla->enqueueMessage(JText::_('PLG_ZLELEMENTS_ZLFW_MISSING'), 'notice');
			} else {
				// load zoo
				require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

				// fix plugins ordering
				$zoo = App::getInstance('zoo');
				$zoo->loader->register('ZlfwHelper', 'root:plugins/system/zlframework/zlframework/helpers/zlfwhelper.php');
				$zoo->zlfw->checkPluginOrder('zoo_zlelements');
			}
		}
	}
}