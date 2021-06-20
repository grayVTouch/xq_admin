
export default {

    show (id) {
        return Http.get(`${TopContext.api}/video_project/${id}`);
    } ,

    index (query) {
        return Http.get(`${TopContext.api}/video_project` , query);
    } ,

    newest (query) {
        return Http.get(`${TopContext.api}/video_project/newest` , query);
    } ,

    hotTags (query) {
        return Http.get(`${TopContext.api}/video_project/hot_tags` , query);
    } ,

    hotTagsWithPager (query) {
        return Http.get(`${TopContext.api}/video_project/hot_tags_with_pager`, query);
    } ,

    hot (query) {
        return Http.get(`${TopContext.api}/video_project/hot`, query);
    } ,

    getByTagId (query) {
        return  Http.get(`${TopContext.api}/video_project/get_by_tag_id` , query);
    } ,

    getByTagIds (query) {
        return Http.get(`${TopContext.api}/video_project/get_by_tag_ids` , query);
    } ,

    videosInRange (id , query) {
        return Http.get( `${TopContext.api}/video_project/${id}/videos_in_range`, query);
    } ,

    vdieoProjectFilterByVideoSeriesId (query) {
        return Http.get(`${TopContext.api}/video_project_filter_by_video_series_id` , query);
    } ,

    categories () {
        return Http.get(`${TopContext.api}/video_project/category`);
    } ,

    praiseHandle (id , query , data) {
        return Http.post(`${TopContext.api}/video_project/${id}/praise_handle` , query , data);
    } ,

};
