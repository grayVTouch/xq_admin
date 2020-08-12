const search = genUrl('search_user');

const index = genUrl('user');
const store = genUrl('user');
const update = genUrl('user/{id}');
const show = genUrl('user/{id}');
const destroy = genUrl('user/{id}');
const destroyAll = genUrl('destroy_all_user');

export default {
    search (data , success , error) {
        return request(search , 'get' , data , success , error);
    } ,

    index (data , success , error) {
        return request(index , 'get' , data , success , error);
    } ,

    update (id , data , success , error) {
        const url = update.replace('{id}' , id);
        return request(url , 'put' , data , success , error)
    } ,

    store (data , success , error) {
        return request(store , 'post' , data , success , error)
    } ,

    show (id , success , error) {
        const url = show.replace('{id}' , id);
        return request(show , 'get' , null , success , error)
    } ,

    destroy (id , success , error) {
        const url = destroy.replace('{id}' , id);
        return request(url , 'delete' , null , success , error)
    } ,

    destroyAll (idList , success , error) {
        return request(destroyAll , 'delete' , {
            ids: G.jsonEncode(idList)
        } , success , error)
    } ,
};
