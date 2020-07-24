// import logo from "../res/logo.jpg";
// import avatar from "../res/avatar.jpg";
// import notFound from "../res/404.png";
import business from './business.js';
import table from './table.js';
import config from './config.js';

/**
 * ******************
 * 全局上下文环境
 * ******************
 */
window.TopContext = {
    host: 'http://xq.test' ,
    api: 'http://api.xq.test/api/web_v1' ,
    // // 图片上传 api
    fileApi: 'http://api.xq.test/api/web_v1/upload' ,

    code: {
        Success: 200 ,
        AuthFailed: 401 ,
        FormValidateFail: 400 ,
    } ,
    res: {
        logo: 'http://api.xq.test/upload/20200712/phjK4tBEQWX8BiIb0ue3kdpfcEwBOGQNCODkpbUK.png' ,
        notFound: 'http://api.xq.test/upload/20200712/ecrT5SPDzkUQZWPit3qk3L5Vb9GYaoXLKoTiUBMY.jpeg' ,
        avatar: 'http://api.xq.test/upload/20200712/VJ8BKpDWTy1oVg1XDgaSOufmGcwcA0zEx5Q6Qxgw.png' ,

        user: {
            password: '' ,

        } ,
    } ,
    business ,
    table ,
    // 每页显示记录数
    limit: 20 ,
    config ,
    val: {
        fixedTop: 105 ,
        headerH: 105 ,
        footerH: 130 ,
    } ,

    os: {
        name: '兴趣部落' ,
        version: '1.0.0' ,
        author: 'running'
    } ,
};