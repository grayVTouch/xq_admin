const index = genUrl('tag');
const store = genUrl('tag');
const update = genUrl('tag/{id}');
const show = genUrl('tag/{id}');
const destroy = genUrl('tag/{id}');
const destroyAll = genUrl('destroy_all_tag');
const search = genUrl('search_tag');
const top = genUrl('top_tag');

export default {
    index (data , success , error) {
        return request(index , 'get' , data , success , error);
    } ,

    localUpdate (id , data , success , error) {
        const url = localUpdate.replace('{id}' , id);
        return request(url , 'patch' , data , success , error)
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

    search (value , success , error) {
        return request(search , 'get' , {
            value ,
        } , success , error);
    } ,

    top (success , error) {
        return request(top , 'get' , null , success , error);
    } ,
};