const path = require('path');
// 使用了 clean-webpack-plugin & html-webpack-plugin 插件后
// 结合 webpack-dev-server 进行开发时，编译后文件会常驻内存
// 且 ./dist 目录会被删除！！
// 也就是说会发生 dist 目录消失的现象。
// 注意该插件的官方用法发生变更，如果使用的最新版的
// 请更新成以下这种写法
// 更新这种写法的主要原因是
// 目前猜测是因为没有默认导出，允许自定义接收
// 自己需要的部分
// const CleanWebpackPlugin = require('clean-webpack-plugin');


const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    entry: {
        // babel-polyfill，在 ie 环境下，vuex 需要用到！
        // 相关文档请看 babel 官方文档
        app: ['@babel/polyfill' , './src/app.js']
    },
    plugins: [
        // 这个用法错了
        // new CleanWebpackPlugin(['compiled']),
        new CleanWebpackPlugin({
            // 仅删除陈旧的资源
            cleanStaleWebpackAssets: false ,
        }) ,
        new HtmlWebpackPlugin({
            title: '兴趣部落' ,
            filename: 'index.html' ,
            template: 'template.html'
        }) ,
        new VueLoaderPlugin() ,
        new MiniCssExtractPlugin({
            filename: "css/[name]-[hash].css",
            chunkFilename: "css/chunk-[name]-[hash].css"
        })
    ],
    output: {
        filename: 'js/[name]-[hash].js',
        path: path.resolve(__dirname, 'dist') ,
        chunkFilename: "js/chunk-[name]-[hash].js" ,
    } ,
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: [
                    {
                        loader: 'babel-loader' ,
                        options: {
                            presets: ["@babel/preset-env"] ,
                            plugins: [
                                // 提升运行速度 详情请查看 https://webpack.js.org/loaders/babel-loader/#root
                                '@babel/plugin-transform-runtime' ,
                                // 支持动态导入语法 import()
                                '@babel/plugin-syntax-dynamic-import' ,
                                // iview 组件动态加载
                                [
                                    "import" ,
                                    {
                                        "libraryName": "iview" ,
                                        "libraryDirectory": "src/components"
                                    }
                                ] ,
                            ]
                        }
                    }
                ]
            } ,
            {
                test: /\.s?[ac]ss$/,
                // test: /\.css$/ ,
                use: [
                    // MiniCssExtractPlugin.loader ,
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
                    // 'vue-style-loader' ,
                    // 'style-loader' ,
                    {
                        loader: 'css-loader' ,
                        options: {
                            sourceMap: true
                        }
                    }
                ],
            } ,
            {
                test: /\.(png|svg|jpg|gif)$/,
                use: [
                    {
                        // 请使用该文件加载器
                        // 它能够复制在 js 中通过 import 加载的文件
                        // 也能够复制在 css 中通过
                        // background: url('test.jpg') 这种方式引入的文件
                        // 是一种加强型的 文件加载器
                        loader: 'url-loader' ,
                        options: {
                            name: 'res/[name]-[hash].[ext]' ,
                            esModule: false ,
                        }

                    }
                ]
            } ,
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    {
                        loader: 'file-loader' ,
                        options: {
                            name: 'font/[name]-[hash].[ext]' ,
                            // esModule: false ,
                        } ,
                    }
                ]
            } ,
            {
                test: /\.(csv|tsv)$/,
                use: [
                    'csv-loader'
                ]
            } ,
            {
                test: /\.xml$/ ,
                use: [
                    'xml-loader'
                ]
            } ,
            {
                test: /\.vue$/ ,
                loader: 'vue-loader'
            }
        ]
    } ,
    // 相关依赖
    // 简化导入
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm.js' ,
            'vue-router': 'vue-router/dist/vue-router.esm.js' ,
            'vuex': 'vuex/dist/vuex.esm.js' ,

            '@asset': path.resolve(__dirname , './src/asset') ,
            '@api': path.resolve(__dirname , './src/api') ,
            '@plugin': path.resolve(__dirname , './src/plugin') ,
            '@vue': path.resolve(__dirname , './src/vue') ,
        }
    } ,

};
