/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

import Vue from 'vue';
import VueResource from '../src/index';

Vue.use(VueResource);

require('./url.js');
require('./http.js');
require('./promise.js');
