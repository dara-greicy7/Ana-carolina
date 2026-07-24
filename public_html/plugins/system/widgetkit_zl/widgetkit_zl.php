<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

class plgSystemWidgetkit_zl extends JPlugin
{
    public function onAfterInitialise()
    {
        if (!($app = (@include JPATH_ADMINISTRATOR.'/components/com_widgetkit/widgetkit-app.php')
            and $wk_config = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/com_widgetkit/widgetkit.xml')
            and version_compare($wk_config->version, '2.3', '>=')
            and file_exists($path = JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')
            and JComponentHelper::getComponent('com_zoo', true)->enabled
            and (include_once $path)
            and class_exists('App')
            and $zoo = App::getInstance('zoo')
            and version_compare($zoo->zoo->version(), '2.5', '>=')
            and JPluginHelper::getPlugin('system', 'widgetkit_zoo'))
        ) {
            return;
        }

        $zoo->event->dispatcher->connect('layout:init', function($event) use($app) {

            $event->setReturnValue(array_merge($event->getReturnValue(), array(array(
                'name' => 'WK ZOO Pro',
                'path' => $app['locator']->find('plugins/content/zoopro'),
                'type' => 'plugin'
            ))));

        });

        $app['plugins']->addPath(__DIR__.'/plugin/plugin.php');
        $app['locator']->addPath('plugins/content/zoopro', __DIR__.'/plugin');
    }
}
