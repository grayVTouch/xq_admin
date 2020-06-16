const index = genUrl('admin_permission');
const store = genUrl('admin_permission');
const localUpdate = genUrl('admin_permission/{id}');
const update = genUrl('admin_permission/{id}');
const show = genUrl('admin_permission/{id}');
const destroy = genUrl('admin_permission/{id}');
const destroyAll = genUrl('destroy_all_admin_permission');
const permissionExcludeSelfAndChildren = genUrl('admin_permission/{id}/all');

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
            ids: G.jsonEncode(idList)
        } , success , error)
    } ,

    permissionExcludeSelfAndChildren (id , success , error) {
        return request(permissionExcludeSelfAndChildren.replace('{id}' , id) , 'get' , null , success , error)
    } ,

};