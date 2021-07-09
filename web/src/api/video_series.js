
export default {

    index (query) {
        return Http.get(`${TopContext.api}/video_series` , query);
    } ,

    show (id) {
        return Http.get(`${TopContext.api}/video_series/${id}`);
    } ,
};
