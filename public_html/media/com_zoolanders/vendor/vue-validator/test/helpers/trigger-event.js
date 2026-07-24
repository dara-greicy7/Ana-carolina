/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

window.triggerEvent = function triggerEvent (target, event, process) {
  const e = document.createEvent('HTMLEvents')
  e.initEvent(event, true, true)
  process && process(e)
  target.dispatchEvent(e)
}
