const imageSubject = `${TopContext.api}/image_at_position/image_subject`;
const newestWithPager = `${TopContext.api}/image_subject/newest_with_pager`;
const hotWithPager = `${TopContext.api}/image_subject/hot_with_pager`;
const hotTags = `${TopContext.api}/image_subject/hot_tags`;
const hotTagsWithPager = `${TopContext.api}/image_subject/hot_tags_with_pager`;
const getWithPagerByTagIds = `${TopContext.api}/image_subject/get_with_pager_by_tag_ids`;
const show = `${TopContext.api}/image_subject/{id}`;

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
};