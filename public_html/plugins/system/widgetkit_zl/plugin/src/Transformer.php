<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

namespace YOOtheme\Widgetkit\Joomla\Zoopro;

use YOOtheme\Widgetkit\Joomla\Zoo\Transformer as baseTransformer;

class Transformer extends baseTransformer
{
    public function renderFile($event, $element)
    {
        $event['value'] = $element->get('file');
    }

    public static function getSubscribedEvents()
    {
        return array(
            'joomla.zoo.render.imagepro'      => 'renderFile',
            'joomla.zoo.render.downloadpro'   => 'renderFile',
            'joomla.zoo.render.mediapro'      => 'renderMedia',
            'joomla.zoo.render.googlemapspro' => 'renderGooglemaps',
            'joomla.zoo.render.itemlinkpro'   => 'renderItemlink'
        );
    }
}
