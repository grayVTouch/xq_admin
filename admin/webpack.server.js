const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = merge(common, {
    mode: 'development',
    devtool: 'inline-source-map',
    devServer: {
        // web 网站根目录
        contentBase: './dist' ,
        // 初始访问的文件
        index: 'index.html' ,
        // Ip
        host: '127.0.0.1' ,
        // 端口
        port: 30000 ,
        // 当模块热更新失败的时候，刷新页面。
        // 和 hotOnly 不同弄（失败仅在控制台输出错误）
        hot: true ,
        // 当热加载失败的时候刷新页面，请务必开启
        // 否则当修改js文件后，页面会提示要求实例重新加载
        // 实际上就是要求刷新页面
        // 故而，为了让他自动刷新页面
        // 所以这边需要开启
        // inline: true ,
        // 不显示错误消息
        noInfo: false ,
        // 自动打开默认浏览器
        open: true ,
        // 全屏错误
        overlay: true ,
    } ,
    rules: [
        {
            // test: /\.s?[ac]ss$/,
            test: /\.css$/ ,
            use: [
                {
                    loader: MiniCssExtractPlugin.loader,
                    options: {
                        /**
                         * 如果没加 publicPath 的情况下，css 中通过 @import 或 url() 等引入的文件
                         * 加载的目录会默认是 css 文件所在目录
                         * 而实际上字体文件的定位是 dist 目录所在目录！
                         * 所以需要给出 publicPath 指定 dist 编译的根目录
                         */
                        publicPath: '../',
                    },
                },
                {
                    loader: 'css-loader' ,
                    options: {
                        esModule: false ,
                    }
                } ,
            ],
        } ,
        // {
        //     // test: /\.s?[ac]ss$/,
        //     test: /\.css$/,
        //     use: [
        //         'vue-style-loader',
        //         {
        //             loader: 'css-loader',
        //             options: {
        //                 esModule: false,
        //             }
        //         },
        //     ]
        // } ,
    ] ,
});
