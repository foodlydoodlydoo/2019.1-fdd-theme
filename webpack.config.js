/* global process __dirname */
const DEV = process.env.NODE_ENV !== 'production';

const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

// We need the website's root folder.
const appPath = `${path.resolve(__dirname, '..', '..', '..')}`;

// Dev Server
const proxyUrl = 'fdd.local:8081';

// Theme
const themeName = path.basename(__dirname);
const themePath = `/wp-content/themes/${themeName}/skin`;
const themeNodePath = `${appPath}/wp-content/themes/${themeName}/node_modules`;
const themeFullPath = `${appPath}${themePath}`;
const themePublicPath = `${themePath}/public/`;
const themeEntry = `${themeFullPath}/assets/application.js`;
const themeAdminEntry = `${themeFullPath}/assets/application-admin.js`;
const themeOutput = `${themeFullPath}/public`;

// Outputs
const outputHash = `${DEV ? '[name]' : '[name]-[hash]'}`;
const outputStatic = '[name].[ext]';


// All loaders to use on assets.
const allModules = {
  rules: [
    {
      test: /\.(js|jsx)$/,
      exclude: /node_modules/,
      use: 'babel-loader',
    },
    {
      test: /\.json$/,
      exclude: /node_modules/,
      use: `file-loader?name=${outputStatic}`,
    },
    {
      test: /\.(png|svg|jpg|jpeg|gif|ico)$/,
      exclude: [/fonts/, /node_modules/],
      use: `file-loader?name=${outputStatic}`,
    },
    {
      test: /\.(eot|otf|ttf|woff|woff2|svg)$/,
      exclude: [/images/, /node_modules/],
      use: `file-loader?name=${outputStatic}`,
    },
    {
      test: /\.scss$/,
      exclude: /node_modules/,
      use: [
        MiniCssExtractPlugin.loader,
        'css-loader', 'postcss-loader', 'sass-loader',
      ],
    },
  ],
};

// All plugins to use.
const allPlugins = [

  // Convert JS to CSS.
  new MiniCssExtractPlugin({
    filename: `${outputHash}.css`,
  }),

  // Gives you jQuery with in the webpack so no need for impoting it.
  new webpack.ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery',
  }),

  // Create manifest.json file.
  new ManifestPlugin({ seed: {} }),
];

// General optimisations.
const allOptimizations = {
  runtimeChunk: false,
  splitChunks: {
    cacheGroups: {
      commons: {
        test: /[\\/]node_modules[\\/]/,
        name: 'vendors',
        chunks: 'all',
      },
    },
  },
};

// Use only for production build
if (!DEV) {
  allOptimizations.minimizer = [

    // Optimise for production.
    new TerserPlugin({
      cache: true,
      parallel: true,
      sourceMap: true,
    }),
  ];

  // Delete public folder.
  allPlugins.push(new CleanWebpackPlugin());
}

module.exports = [

  // Theme Skin
  {
    context: path.join(__dirname),
    entry: {
      application: [themeEntry],
      applicationAdmin: [themeAdminEntry],
    },
    output: {
      path: themeOutput,
      publicPath: themePublicPath,
      filename: `${outputHash}.js`,
    },

    // Don't bundle jQuery but expect it from a different source.
    externals: {
      jquery: 'jQuery',
    },

    optimization: allOptimizations,

    module: allModules,

    plugins: allPlugins,

    devtool: DEV ? '' : 'source-map',
  },
];
