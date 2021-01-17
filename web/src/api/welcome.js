const module = `${TopContext.api}/module`;

export default {

    //模块列表
    module () {
        return Http.get(module);
    } ,

};
