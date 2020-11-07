const login = genUrl('/login');
const avatar = genUrl('/avatar');

export default {
    login (data) {
        return request(login , 'post' , null , data);
    } ,

    avatar (param) {
        return request(avatar , 'get' , param);
    } ,
};
