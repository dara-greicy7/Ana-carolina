/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

module.exports = {
    entry: __dirname + "/index.js",
    output: {
        path: __dirname + "/",
        filename: "specs.js"
    },
    module: {
        loaders: [
            {test: /.js/, exclude: /node_modules/, loader: 'babel', query: {presets: ['es2015']}}
        ]
    }
};
