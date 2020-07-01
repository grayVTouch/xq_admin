const homeSlideshow = `${TopContext.api}/image_at_position/home_slideshow`;
const newestInImageSubject = `${TopContext.api}/image_subject/newest`;
const hotInImageSubject = `${TopContext.api}/image_subject/hot`;
const hotTags = `${TopContext.api}/image_subject/hot_tags`;
const getImageByTagId = `${TopContext.api}/image_subject/{tag_id}/get_by_tag_id`;
const getImageByTagIds = `${TopContext.api}/image_subject/get_by_tag_ids`;

export default {

    homeSlideshow (success , error) {
        return request(homeSlideshow , 'get' , null , success , error);
    } ,

    newestInImageSubject (data , success , error) {
        return request(newestInImageSubject, 'get' , data , success , error);
    } ,

    hotInImageSubject (data , success , error) {
        return request(hotInImageSubject, 'get' , data , success , error);
    } ,

    hotTags (data , success , error) {
        return request(hotTags, 'get' , data , success , error);
    } ,

    getImageByTagId (tagId , data , success , error) {
        return request(getImageByTagId.replace('{tag_id}' , tagId), 'get' , data , success , error);
    } ,

    getImageByTagIds (data , success , error) {
        return request(getImageByTagIds , 'get' , data , success , error);
    } ,
};