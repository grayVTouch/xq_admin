/**
 * 请求拦截器
 */
G.ajax.created = function(){
    // 这边可以做一些什么
    return true;
};

/**
 * 响应拦截器
 */
G.ajax.responded = function(data , code){
    if (code === TopContext.code.AuthFailed) {
        // 登录认证失败
        G.cookie.del('token');
        window.history.go(0);
        return false;
    }
    return true;
};
/**
 * 网络请求
 *
 * @author running
 *
 * @param url
 * @param method
 * @param param
 * @param data
 * @returns {Promise}
 */
window.request = (url , method , param , data) =>
{
    return new Promise((resolve , reject) => {
        const token = G.cookie.get('token');
        return G.ajax({
            url ,
            method ,
            header: {
                // 授权码
                Authorization: token ? token : '' ,
                // 接收的服务端响应的数据类型
                Accept: 'application/json' ,
            } ,
            param ,
            data ,
            success (data , code) {
                data.code = code;
                resolve(data);
            } ,
            error (data) {
                reject(data);
            } ,
            timeout (data) {
                reject(data);
            } ,
        });
    });
};
