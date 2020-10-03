const login = genUrl('/login');
const avatar = genUrl('/avatar');

export default {
    login (data , success , error) {
        return request(login , 'post' , data , success , error);
    } ,

    avatar (data , success , error) {
        return request(avatar , 'get' , data , success , error);
    } ,
};