const index = `${TopContext.api}/video_subject`;
const show = `${TopContext.api}/video_subject/{id}`;


const newest = `${TopContext.api}/video_subject/newest`;
const hot = `${TopContext.api}/video_subject/hot`;
const hotTags = `${TopContext.api}/video_subject/hot_tags`;
const getByTagId = `${TopContext.api}/video_subject/{tag_id}/get_by_tag_id`;
const getByTagIds = `${TopContext.api}/video_subject/get_by_tag_ids`;
const videosInRange = `${TopContext.api}/video_subject/{id}/videos_in_range`;
const videoSubjects = `${TopContext.api}/video_subject/{id}/video_subjects`;

export default {

    show (id , success , error) {
        return request(show.replace('{id}' , id) , 'get' , null , success , error);
    } ,

    index (data , success , error) {
        return request(index , 'get' , data , success , error);
    } ,

    newest (data , success , error) {
        return request(newest, 'get' , data , success , error);
    } ,

    hotTags (data , success , error) {
        return request(hotTags, 'get' , data , success , error);
    } ,

    hot (data , success , error) {
        return request(hot, 'get' , data , success , error);
    } ,

    getByTagId (tagId , data , success , error) {
        return request(getByTagId.replace('{tag_id}' , tagId), 'get' , data , success , error);
    } ,

    getByTagIds (data , success , error) {
        return request(getByTagIds , 'get' , data , success , error);
    } ,

    videosInRange (id , data , success , error) {
        return request(videosInRange.replace('{id}' , id), 'get' , data , success , error);
    } ,

    videoSubjects (id , data , success , error) {
        return request(videoSubjects.replace('{id}' , id), 'get' , data , success , error);
    } ,

};