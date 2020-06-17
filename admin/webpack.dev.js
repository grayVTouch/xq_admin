const merge = require('webpack-merge');
const common = require('./webpack.common.js');

module.exports = merge(common, {
    mode: 'development',
    devtool: 'inline-source-map',
    devServer: {
        // web 网站根目录
        contentBase: './compiled' ,
        // 初始访问的文件
        index: 'index.html' ,
        // Ip
        host: '127.0.0.1' ,
        // 端口
        port: 9000
    }
});