/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

const base = require('./karma.base.conf')

module.exports = config => {
  config.set(Object.assign(base, {
    reporters: ['progress'],
    browsers: ['Chrome', 'Firefox', 'Safari'],
    singleRun: true
  }))
}
