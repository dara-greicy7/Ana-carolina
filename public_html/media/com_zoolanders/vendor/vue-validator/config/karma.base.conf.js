/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

const webpack = require('webpack')

const webpackConfig = {
  module: {
    loaders: [{
      test: /\.js$/,
      exclude: /node_modules|vue\/dist/,
      loader: 'babel!eslint'
    }],
    postLoaders: [{
      test: /\.json$/,
      loader: 'json'
    }]
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"development"'
      }
    })
  ],
  devtool: '#inline-source-map'
}

module.exports = {
  basePath: '',
  files: [
    '../test/unit/index.js'
  ],
  exclude: [
  ],
  frameworks: ['jasmine'],
  preprocessors: {
    '../test/unit/index.js': ['webpack', 'sourcemap']
  },
  webpack: webpackConfig,
  webpackMiddleware: {
    noInfo: true
  }
}
