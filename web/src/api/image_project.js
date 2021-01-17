const index = `${TopContext.api}/image_project`;

const imageSubject = `${TopContext.api}/image_at_position/image_project`;
const newestWithPager = `${TopContext.api}/image_project/newest_with_pager`;
const hotWithPager = `${TopContext.api}/image_project/hot_with_pager`;
const hotTagsWithPager = `${TopContext.api}/image_project/hot_tags_with_pager`;
const getWithPagerByTagIds = `${TopContext.api}/image_project/get_with_pager_by_tag_ids`;
const categories = `${TopContext.api}/image_project/category`;
const subjects = `${TopContext.api}/image_project/subject`;
const hot = `${TopContext.api}/image_project/hot`;
const getByTagId = `${TopContext.api}/image_project/{tag_id}/get_by_tag_id`;
const getByTagIds = `${TopContext.api}/image_project/get_by_tag_ids`;

export default {

    show (id) {
        return Http.get(`${TopContext.api}/image_project/${id}`);
    } ,

    index (data) {
        return request(index , 'get' , data);
    } ,

    imageSubject () {
        return request(imageSubject , 'get' , null);
    } ,

    newestWithPager (data) {
        return request(newestWithPager, 'get' , data);
    } ,

    hotWithPager (data) {
        return request(hotWithPager, 'get' , data);
    } ,

    hotTags (query) {
        return Http.get(`${TopContext.api}/image_project/hot_tags` , query);
    } ,

    hotTagsWithPager (data) {
        return request(hotTagsWithPager, 'get' , data);
    } ,

    getWithPagerByTagIds (data) {
        return request(getWithPagerByTagIds , 'get' , data);
    } ,

    categories () {
        return request(categories , 'get' , null);
    } ,

    subjects (data) {
        return request(subjects , 'get' , data);
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
        return Http.get(hot , query);
    } ,

    // 图片专题：根据标签返回
    getByTagId (tagId , query) {
        return Http.get(`${TopContext.api}/image_project/${tagId}/get_by_tag_id` , query);
    } ,

    getByTagIds (data) {
        return request(getByTagIds , 'get' , data);
    } ,

};
