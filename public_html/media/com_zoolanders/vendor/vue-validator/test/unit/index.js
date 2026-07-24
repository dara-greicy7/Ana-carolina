/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

// import all helpers
const helpersContext = require.context('../helpers', true)
helpersContext.keys().forEach(helpersContext)

// require all test files
const testsContext = require.context('./', true, /\.test$/)
testsContext.keys().forEach(testsContext)
