/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

const pack = require('../package.json')
const version = process.env.VERSION || pack.version

module.exports =
  '/*!\n' +
  ` * ${pack.name} v${version} \n` +
  ` * (c) ${new Date().getFullYear()} ${pack.author.name}\n` +
  ` * Released under the ${pack.license} License.\n` +
  ' */'
