

window.genUrl = function(path){
    const api = TopContext.api.replace(/^\// , '');
    path = path.replace(/^\// , '');
    return TopContext.api + '/' + path;
};
