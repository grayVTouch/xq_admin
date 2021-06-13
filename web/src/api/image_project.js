
export default {

    show (id) {
        return Http.get(`${TopContext.api}/image_project/${id}`);
    } ,

    index (query) {
        return Http.get(`${TopContext.api}/image_project` , query);
    } ,

    newestWithPager (query) {
        return Http.get(`${TopContext.api}/image_project/newest_with_pager`, query);
    } ,

    hotWithPager (query) {
        return Http.get(`${TopContext.api}/image_project/hot_with_pager` , query);
    } ,

    hotTags (query) {
        return Http.get(`${TopContext.api}/image_project/hot_tags` , query);
    } ,

    hotTagsWithPager (query) {
        return Http.get(`${TopContext.api}/image_project/hot_tags_with_pager`, query);
    } ,

    getWithPagerByTagIds (query) {
        return Http.get(`${TopContext.api}/image_project/get_with_pager_by_tag_ids` , query);
    } ,

    categories () {
        return Http.get(`${TopContext.api}/image_project/category`);
    } ,

    imageSubjects (query) {
        return Http.get( `${TopContext.api}/image_project/image_subject` , query);
    } ,

    incrementViewCount (id) {
        return Http.post( `${TopContext.api}/image_project/${id}/increment_view_count`);
    } ,

    recommend (id , query) {
        return Http.get(`${TopContext.api}/image_project/${id}/recommend` , query);
    } ,

    // 最新的图片专题
    newest (query) {
        return Http.get(`${TopContext.api}/image_project/newest` , query);
    } ,

    // 最热的图片专题
    hot (query) {
        return Http.get(`${TopContext.api}/image_project/hot` , query);
    } ,

    // 图片专题：根据标签返回
    getByTagId (query) {
        return Http.get(`${TopContext.api}/image_project/get_by_tag_id` , query);
    } ,

    getByTagIds (query) {
        return Http.get(`${TopContext.api}/image_project/get_by_tag_ids` , query);
    } ,

};
