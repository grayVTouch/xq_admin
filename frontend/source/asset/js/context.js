import logo from "../res/logo.jpg";
import notFound from "../res/404.png";
import business from './business.js';
import table from './table.js';

/**
 * ******************
 * 全局上下文环境
 * ******************
 */
window.TopContext = {
    api: 'http://xq.test/api/v1' ,
    // 图片上传 api
    fileApi: 'http://xq.test/api/v1/upload' ,
    successCode: 200 ,
    res: {
        logo ,
        notFound ,
    } ,
    business ,
    table ,
    // 每页显示记录数
    limit: 20 ,
};