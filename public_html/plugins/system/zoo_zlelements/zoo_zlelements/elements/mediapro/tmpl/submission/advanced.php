<?php
/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die;

if ($layout = $this->getLayout("edit/default.php"))
{
	echo $this->renderLayout($layout, compact('params', 'trusted_mode'));
}