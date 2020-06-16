const info = genUrl('info');
const search = genUrl('search_user');

export default {
    info (success , error) {
        return request(info , 'get' , null , success , error);
    } ,

    search (value , success , error) {
        return request(search , 'get' , {
            value ,
        } , success , error);
    } ,
};
