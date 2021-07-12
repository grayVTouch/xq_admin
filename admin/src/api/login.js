const login = genUrl('/login');
const avatar = genUrl('/avatar');

export default {
    login (data) {
        return Http.post(login , null , data);
    } ,

    avatar (query) {
        return Http.get(avatar , query);
    } ,

    settings () {
        return Http.get(`${TopContext.api}/login_settings`);
    } ,
};
