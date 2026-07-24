/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

import Vue from 'vue'
import assert from 'power-assert'
import plugin from '../../src/index'
import 'babel-polyfill' // promise and etc ...

Vue.use(plugin)

window.Vue = Vue
window.assert = assert
