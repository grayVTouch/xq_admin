const show = `${TopContext.api}/category/{id}`;

export default {
    show (id) {
        return request(show.replace('{id}' , id) , 'get' , null);
    } ,
};
