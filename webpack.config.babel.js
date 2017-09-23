'use strict';
import path from 'path';
import webpack from 'webpack';

const config = {
    entry: {
        bundle: path.join(__dirname, '/client/src/bundles/bundle')
    },
    output: {
        path: path.join(__dirname, '/client/dist'),
        filename: '[name].min.js'
    },
    resolve: {
        extensions: ["", ".webpack.js", ".jsx", ".js"]
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            }
        ]
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify('production')
            }
        })/*,
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        })*/
    ],
    externals: {
        'jquery': 'jQuery'
    }
};

module.exports = config;

