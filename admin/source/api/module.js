const index = genUrl('module');
const store = genUrl('module');
const update = genUrl('module/{id}');
const show = genUrl('module/{id}');
const destroy = genUrl('module/{id}');
const destroyAll = genUrl('destroy_all_module');
const all = genUrl('get_all_module');

export default {
    index (data , success , error) {
        return request(index , 'get' , data , success , error);
    } ,

    localUpdate (id , data , success , error) {
        const url = localUpdate.replace('{id}' , id);
        return request(url , 'patch' , data , success , error);
    } ,

    update (id , data , success , error) {
        const url = update.replace('{id}' , id);
        return request(url , 'put' , data , success , error);
    } ,

    store (data , success , error) {
        return request(store , 'post' , data , success , error);
    } ,

    show (id , success , error) {
        const url = show.replace('{id}' , id);
        return request(show , 'get' , null , success , error);
    } ,

    destroy (id , success , error) {
        const url = destroy.replace('{id}' , id);
        return request(url , 'delete' , null , success , error);
    } ,

    destroyAll (idList , success , error) {
        return request(destroyAll , 'delete' , {
            ids: G.jsonEncode(idList)
        } , success , error);
    } ,

    all (success , error) {
        return request(all , 'get' , null , success , error)
    } ,
};