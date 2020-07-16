const imageSubject = `${TopContext.api}/image_at_position/image_subject`;
const newestWithPager = `${TopContext.api}/image_subject/newest_with_pager`;
const hotWithPager = `${TopContext.api}/image_subject/hot_with_pager`;
const hotTags = `${TopContext.api}/image_subject/hot_tags`;
const hotTagsWithPager = `${TopContext.api}/image_subject/hot_tags_with_pager`;
const getWithPagerByTagIds = `${TopContext.api}/image_subject/get_with_pager_by_tag_ids`;
const show = `${TopContext.api}/image_subject/{id}`;
const categories = `${TopContext.api}/image_subject/category`;
const subjects = `${TopContext.api}/image_subject/subject`;
const index = `${TopContext.api}/image_subject`;
const incrementViewCount = `${TopContext.api}/image_subject/{image_subject_id}/increment_view_count`;
const recommend = `${TopContext.api}/image_subject/{image_subject_id}/recommend`;
const newest = `${TopContext.api}/image_subject/newest`;
const hot = `${TopContext.api}/image_subject/hot`;

export default {

    imageSubject (success , error) {
        return request(imageSubject , 'get' , null , success , error);
    } ,

    newestWithPager (data , success , error) {
        return request(newestWithPager, 'get' , data , success , error);
    } ,

    hotWithPager (data , success , error) {
        return request(hotWithPager, 'get' , data , success , error);
    } ,

    hotTags (data , success , error) {
        return request(hotTags, 'get' , data , success , error);
    } ,

    hotTagsWithPager (data , success , error) {
        return request(hotTagsWithPager, 'get' , data , success , error);
    } ,

    getWithPagerByTagIds (data , success , error) {
        return request(getWithPagerByTagIds , 'get' , data , success , error);
    } ,

    show (id , success , error) {
        return request(show.replace('{id}' , id) , 'get' , null , success , error);
    } ,

    categories (success , error) {
        return request(categories , 'get' , null , success , error);
    } ,

    subjects (data , success , error) {
        return request(subjects , 'get' , data , success , error);
    } ,

    index (data , success , error) {
        return request(index , 'get' , data , success , error);
    } ,

    incrementViewCount (id , success , error) {
        return request(incrementViewCount.replace('{image_subject_id}' , id) , 'patch' , null , success , error);
    } ,

    recommend (id , data , success , error) {
        return request(recommend.replace('{image_subject_id}' , id) , 'get' , data , success , error);
    } ,

    newest (data , success , error) {
        return request(newest , 'get' , data , success , error);
    } ,

    hot (data , success , error) {
        return request(hot , 'get' , data , success , error);
    } ,


};