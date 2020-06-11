window.request = function(url , method , data , success , error)
{
    const token = G.s.get('token');
    return G.ajax({
        url ,
        method ,
        header: {
            Authorization: token ,
        } ,
        data ,
        success (data , code) {
            if (code === 401) {
                // 登录认证失败
                G.s.del('token');
                window.history.go(0);
                return ;
            }
            if (G.isFunction(success)) {
                success(data , code);
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