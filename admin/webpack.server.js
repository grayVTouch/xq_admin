const merge = require('webpack-merge');
const common = require('./webpack.common.js');

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
        // 不刷新热更新
        // hot: true ,
        // 当热加载失败的时候刷新页面，请务必开启
        // 否则当修改js文件后，页面会提示要求实例重新加载
        // 实际上就是要求刷新页面
        // 故而，为了让他自动刷新页面
        // 所以这边需要开启
        // inline: true ,
        // 不显示错误消息
        noInfo: true ,
        // 自动打开默认浏览器
        // open: true ,
        // hotOnly: true ,
    }
});
