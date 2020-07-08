const show = `${TopContext.api}/category/{id}`;

export default {
    show (id , success , error) {
        return request(show.replace('{id}' , id) , 'get' , null , success , error);
    } ,
};