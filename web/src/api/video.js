
export default {

    incrementViewCount (id) {
        return Http.post(`${TopContext.api}/video/${id}/increment_view_count`);
    } ,

    incrementPlayCount (id) {
        return Http.post(`${TopContext.api}/video/${id}/increment_play_count`);
    } ,

    praiseHandle (id , query , data) {
        return Http.post(`${TopContext.api}/video/${id}/praise_handle` , query , data);
    } ,

    record (id , query , data) {
        return Http.post(`${TopContext.api}/video/${id}/record` , query , data);
    } ,

};
