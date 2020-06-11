import logo from "../res/logo.jpg";
import business from './business.js';

/**
 * ******************
 * 全局上下文环境
 * ******************
 */
window.TopContext = {
    api: 'http://xq.test/api/v1' ,
    successCode: 200 ,
    res: {
        logo ,
    } ,
    business ,
};