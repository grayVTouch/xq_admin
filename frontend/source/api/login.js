const login = url('/login');
const avatar = url('/avatar');

export default {
    login (data , success , error) {
        return request(login , 'post' , data , success , error);
    } ,

    avatar (data , success , error) {
        return request(avatar , 'get' , data , success , error);
    } ,
};