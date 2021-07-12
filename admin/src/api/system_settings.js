
export default {

    data () {
        return Http.get(`${TopContext.api}/common_settings`);
    } ,

    update (data) {
        return Http.put(`${TopContext.api}/common_settings` , null , data);
    } ,
};
