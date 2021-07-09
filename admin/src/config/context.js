// import logo from "../res/logo.jpg";
// import notFound from "../res/404.png";
import business from './business.js';
import table from './table.js';
import config from './config.js';
import style from './style.js';

/**
 * ******************
 * 全局上下文环境
 * ******************
 */

// 接口 host
const api = 'http://api.xq.test';
// 资源 host
const resUrl = 'http://res.xq.test';

window.TopContext = {
    // 调试模式
    debug: true ,
    api: api + '/api/admin' ,
    resUrl ,
    // 图片上传 api
    fileApi: api + '/api/admin/upload' ,
    uploadApi: api + '/api/admin/upload' ,
    uploadImageApi: api + '/api/admin/upload_image' ,
    uploadVideoApi: api + '/api/admin/upload_video' ,
    uploadSubtitleApi: api + '/api/admin/upload_subtitle' ,
    uploadOfficeApi: api + '/api/admin/upload_office' ,
    code: {
        Success: 200 ,
        AuthFailed: 401 ,
        FormValidateFail: 400 ,
    } ,
    res: {
        logo: resUrl + '/preset/ico/logo.png' ,
        notFound: resUrl + '/preset/image/404.jpg' ,
        avatar: resUrl + '/preset/ico/logo.png' ,
    } ,
    business ,
    table ,
    // 表单信息
    form: {
        itemW: 400 ,
    } ,
    style ,
    // 每页显示记录数
    size: 8 ,
    sizes: [8 , 20 , 50 , 100 , 200] ,
    config ,
    // 系统信息
    os: {
        name: '兴趣部落' ,
    } ,
    val: {
        thumbW: 960 ,
        imageW: 2160 ,
    } ,
    sort: {
        none: 'normal' ,
        asc: 'asc' ,
        desc: 'desc' ,
    } ,
    // 登录时长
    userCredentialsEffectDuration: 8 * 3600 ,
};
