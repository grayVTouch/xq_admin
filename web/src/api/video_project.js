const index = `${TopContext.api}/video_project`;


const getByTagId = `${TopContext.api}/video_project/{tag_id}/get_by_tag_id`;
const getByTagIds = `${TopContext.api}/video_project/get_by_tag_ids`;
const videoSubjects = `${TopContext.api}/video_project/{id}/video_projects`;

export default {

    show (id) {
        return Http.get(`${TopContext.api}/video_project/${id}`);
    } ,

    index (data) {
        return request(index , 'get' , data);
    } ,

    newest (query) {
        return Http.get(`${TopContext.api}/video_project/newest`, query);
    } ,

    hotTags (query) {
        return Http.get(`${TopContext.api}/video_project/hot_tags` , query);
    } ,

    hot (query) {
        return Http.get(`${TopContext.api}/video_project/hot`, query);
    } ,

    getByTagId (tagId , data) {
        return request(getByTagId.replace('{tag_id}' , tagId), 'get' , data);
    } ,

    getByTagIds (data) {
        return request(getByTagIds , 'get' , data);
    } ,

    videosInRange (id , query) {
        return Http.get( `${TopContext.api}/video_project/${id}/videos_in_range`, query);
    } ,

    videoSubjects (id , data) {
        return request(videoSubjects.replace('{id}' , id), 'get' , data);
    } ,

};
