/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

module.exports = {
  add: function (browser) {
    browser
      .url('http://localhost:8080/examples/started/')
      /*
      .waitForElementVisible('p', 1000)
      .assert.containsText('p', '3', 'You should be implemented !!')
      */
      .end()
  }
}
