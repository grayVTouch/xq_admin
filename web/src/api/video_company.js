
export default {

    index (query) {
        return Http.get(`${TopContext.api}/video_company` , query);
    } ,

    show (id) {
        return Http.get(`${TopContext.api}/video_company/${id}`);
    } ,

};
