<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
require_once(dirname(__FILE__).'/remote.php');

/**
 * Class zlHelperDashboard
 */
class zlHelperDashboard extends zlHelperRemote {

	/**
	 * Get ZL server state and get notifications and related data
	 */
	public function getRemoteState(){

		$url = $this->getZlServerURL().'/index.php?option=com_zoo&controller=zooextensions&task=checkAuth&format=raw&zlv='.$this->getZlVersion();
		$url.= $this->_getCredentialsParams();

		return $this->getRemote($url);
	}

	/**
	 * Get cached state
	 */
	public function getCachedState(){

		return $this->_getCache('state', 86400);
	}
}
 