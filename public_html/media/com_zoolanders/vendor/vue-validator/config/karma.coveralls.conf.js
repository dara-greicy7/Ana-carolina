/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

const base = require('./karma.base.conf')

module.exports = config => {
  const options = Object.assign(base, {
    browsers: ['PhantomJS'],
    reporters: ['coverage', 'coveralls'],
    coverageReporter: {
      reporters: [{
        type: 'lcov', dir: '../coverage'
      }]
    },
    singleRun: true
  })

  // add babel-plugin-coverage for code intrumentation
  options.webpack.babel = {
    plugins: [['coverage', { ignore: ['test/'] }]]
  }

  config.set(options)
}
