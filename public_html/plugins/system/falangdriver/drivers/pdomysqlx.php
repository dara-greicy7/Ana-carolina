<?php
defined('JPATH_PLATFORM') or die;
/**
 * MySQLi FaLang database driver
 *
 * @package     Joomla.Platform
 * @subpackage  Database
 * @see         http://php.net/manual/en/book.mysqli.php
 * @since       11.1
 */


class JOverrideDatabase extends JDatabaseDriverMysqli
{
	function __construct($options){
		JFactory::getApplication()->enqueueMessage(JText::_('PLG_SYSTEM_FALANGDRIVER_PDO_NOT_SUPPORTED'));
		parent::__construct($options);
	}
}