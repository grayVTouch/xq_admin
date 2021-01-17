export default {
    // 首页幻灯片
    home () {
        return Http.get(`${TopContext.api}/home_slideshow`);
    } ,

    // 图片专题
    imageProject () {
        return Http.get(`${TopContext.api}/image_project_slideshow`);
    } ,

};
