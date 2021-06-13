const homeSlideshow         = `${TopContext.api}/home_slideshow`;
const newestInImageProjects = `${TopContext.api}/image_project/newest`;
const hotInImageProjects    = `${TopContext.api}/image_project/hot`;
const hotTags               = `${TopContext.api}/image_project/hot_tags`;
const getImageByTagId       = `${TopContext.api}/image_project/get_by_tag_id`;
const getImageByTagIds      = `${TopContext.api}/image_project/get_by_tag_ids`;

export default {

    // 首页幻灯片
    homeSlideshow () {
        return Http.get(homeSlideshow);
    } ,


    newestInImageProjects (query , data) {
        return Http.get(newestInImageProjects, query , data);
    } ,

    hotInImageProjects (query) {
        return Http.get(hotInImageProjects, query);
    } ,

    hotTags (query) {
        return Http.get(hotTags , query);
    } ,

    getImageByTagId (tagId , query) {
        return Http.get(getImageByTagId.replace('{tag_id}' , tagId), query);
    } ,

    getImageByTagIds (query) {
        return Http.get(getImageByTagIds , query);
    } ,
};
