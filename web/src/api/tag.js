
export default {
    show (id) {
        return Http.get( `${TopContext.api}/tag/${id}`);
    } ,
};
