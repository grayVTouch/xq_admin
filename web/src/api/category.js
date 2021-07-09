
export default {
    show (id) {
        return Http.get(`${TopContext.api}/category/${id}`);
    } ,
};
