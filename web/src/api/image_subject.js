
export default {
    show (id) {
        return Http.get(`${TopContext.api}/image_subject/${id}`);
    } ,
};
