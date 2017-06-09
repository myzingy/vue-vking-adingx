var path = require('path')
var webpack = require('webpack')
//const publicPath='dist/';
const HtmlWebpackPlugin = require('html-webpack-plugin')
var plugins = []
plugins.push(
    new HtmlWebpackPlugin({ //根据模板插入css/js等生成最终HTML
        filename: './index.html', //生成的html存放路径，相对于 path
        template: './srcode/template/index.html', //html模板路径
    })
)
if(process.env.NODE_ENV === 'production'){
  var publicPath='dist/';
  var output={
      publicPath:"./",
      path: path.resolve(__dirname + publicPath), //编译到app目录
      filename: 'build.js?[hash]'
  };
}else{
    var publicPath='/dist/';
    var output={
        publicPath:publicPath,
        path: path.resolve(__dirname + publicPath), //编译到app目录
        filename: 'build.js?[hash]'
    };
}
module.exports = {
  entry: './srcode/main.js',
  output: output,
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            // Since sass-loader (weirdly) has SCSS as its default parse mode, we map
            // the "scss" and "sass" values for the lang attribute to the right configs here.
            // other preprocessors should work out of the box, no loader config like this necessary.
            'scss': 'vue-style-loader!css-loader!sass-loader',
            'sass': 'vue-style-loader!css-loader!sass-loader?indentedSyntax',
              //'js':'babel-loader?presets[]=es2015'

          },
          // other vue-loader options go here
        }
      },
      {
        test: /\.css$/,
        loader: 'style-loader!css-loader'
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        //loader: 'babel-loader?presets[]=es2015',
        exclude: /node_modules/,
      },
      {
        test: /\.(eot|svg|ttf|woff|woff2)(\?\S*)?$/,
        loader: 'file-loader'
      },
      {
        //test: /\.(png|jpg|gif|svg)$/,
        test: /\.(png|jpe?g|gif|svg)(\?\S*)?$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]?[hash]'
        }
      }
    ]
  },
  plugins,
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  devServer: {
    historyApiFallback: true,
    noInfo: true,
    //hot: true,
    //inline: true,
    proxy:{
      '/apido': {
        target: 'http://www.vking.com/facebook_ads/wwwroot/',
        //secure: false
      }
    }
  },
  performance: {
    hints: false
  },
  devtool: '#eval-source-map'
}

if (process.env.NODE_ENV === 'production') {
  module.exports.devtool = '#source-map'
  // http://vue-loader.vuejs.org/en/workflow/production.html
  module.exports.plugins = (module.exports.plugins || []).concat([
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true,
      compress: {
        warnings: false
      }
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: true
    })
  ])
}
