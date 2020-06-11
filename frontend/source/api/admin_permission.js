const index = genUrl('/permission');
const store = genUrl('/permission');
const localUpdate = genUrl('permission/{id}');
const update = genUrl('permission/{id}');
const show = genUrl('permission/{id}');
const destroy = genUrl('permission/{id}');
const destroyAll = genUrl('permission/destroy_all');

export default {
    index (success , error) {
        return request(index , 'get' , null , success , error);
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
            id_list: G.jsonEncode(idList)
        } , success , error)
    } ,

};