window.request = function(url , method , data = {} , success , error) {
    const token = G.s.get('token');
    const module = G.s.json('module');
    return G.ajax({
        url ,
        method ,
        header: {
            Authorization: token ,
        } ,
        data: {
            ...data ,
            ...{module_id: module ? module.id : 0}
        } ,
        success (data , code) {
            if (code === 401) {
                // 登录认证失败
                G.s.del('token');
                // G.s.del('user');
                // window.history.go(0);
            }
            if (G.isFunction(success)) {
                success(data.message , data.data , code);
            }
        } ,
        error ,
        netError () {
            // console.log();
        } ,
    });
};

window.genUrl = function(path){
    const api = TopContext.api.replace(/^\// , '');
    path = path.replace(/^\// , '');
    return TopContext.api + '/' + path;
};
