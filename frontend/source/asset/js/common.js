window.request = function(url , method , data , success , error)
{
    return G.ajax({
        url ,
        method ,
        data ,
        success ,
        error ,
        netError () {
            // console.log();
        } ,
    });
};

window.url = function(path){
    const api = topContext.api.replace(/^\// , '');
    path = path.replace(/^\// , '');
    console.log('path' , path);
    return topContext.api + '/' + path;
};