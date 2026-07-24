<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

class plgSystemWidgetkit_zlInstallerScript
{
    public function install($parent)
    {
        // enable plugin only if WK installed and enabled
        if ((@include JPATH_ADMINISTRATOR.'/components/com_widgetkit/widgetkit-app.php') and JComponentHelper::getComponent('com_widgetkit', true)->enabled) {
            JFactory::getDBO()->setQuery("UPDATE `#__extensions` SET `enabled` = 1 WHERE `type` = 'plugin' AND `element` = 'widgetkit_zl'")->execute();
        }
    }

    public function uninstall($parent) {}

    public function update($parent) {}

    public function postflight($type, $parent) {}

}
