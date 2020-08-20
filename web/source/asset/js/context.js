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
// 接口 host
const apiUrl = 'http://api.xq.test';
// 资源 host
const resUrl = 'http://res.xq.test';

window.TopContext = {
    host: 'http://xq.test' ,
    api: apiUrl + '/api/web_v1' ,
    // // 图片上传 api
    fileApi: apiUrl + '/api/web_v1/upload' ,

    // 图片上传 api
    uploadApi: apiUrl + '/api/web_v1/upload' ,
    uploadImageApi: apiUrl + '/api/web_v1/upload_image' ,
    uploadVideoApi: apiUrl + '/api/web_v1/upload_video' ,
    uploadSubtitleApi: apiUrl + '/api/web_v1/upload_subtitle' ,
    uploadOfficeApi: apiUrl + '/api/web_v1/upload_office' ,

    code: {
        Success: 200 ,
        AuthFailed: 401 ,
        FormValidateFail: 400 ,
    } ,
    res: {
        logo: resUrl + '/preset/ico/logo.png' ,
        notFound: resUrl + '/preset/image/404.jpg' ,
        avatar: resUrl + '/preset/ico/logo.png' ,

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
        // 封面图 裁剪尺寸
        thumbW: 960 ,
        // 大图裁剪尺寸
        imageW: 2160 ,
    } ,

    os: {
        name: '兴趣部落' ,
        version: '1.0.0' ,
        author: 'running'
    } ,
};