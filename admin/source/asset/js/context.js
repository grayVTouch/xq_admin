// import logo from "../res/logo.jpg";
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
    api: 'http://api.xq.test/api/admin_v1' ,
    // 图片上传 api
    fileApi: 'http://api.xq.test/api/admin_v1/upload' ,
    code: {
        Success: 200 ,
        AuthFailed: 401 ,
        FormValidateFail: 400 ,
    } ,
    res: {
        logo: 'http://192.168.3.200:10001/upload/20200712/phjK4tBEQWX8BiIb0ue3kdpfcEwBOGQNCODkpbUK.png' ,
        notFound: 'http://192.168.3.200:10001/upload/20200712/ecrT5SPDzkUQZWPit3qk3L5Vb9GYaoXLKoTiUBMY.jpeg' ,
        // avatar: 'http://192.168.3.200:10001/upload/20200712/VJ8BKpDWTy1oVg1XDgaSOufmGcwcA0zEx5Q6Qxgw.png' ,
        avatar: 'http://192.168.3.200:10001/upload/20200712/phjK4tBEQWX8BiIb0ue3kdpfcEwBOGQNCODkpbUK.png' ,
    } ,
    business ,
    table ,
    // 每页显示记录数
    limit: 20 ,
    config ,
    // 系统信息
    os: {
        name: '兴趣部落' ,
    } ,
};