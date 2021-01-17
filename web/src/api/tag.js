const show = `${TopContext.api}/tag/{id}`;

export default {
    show (id) {
        return request(show.replace('{id}' , id) , 'get' , null);
    } ,
};
