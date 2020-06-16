const index = genUrl('role');
const store = genUrl('role');
const update = genUrl('role/{id}');
const show = genUrl('role/{id}');
const destroy = genUrl('role/{id}');
const destroyAll = genUrl('destroy_all_role');
const allocatePermission = genUrl('role/{id}/allocate_permission');
const permission = genUrl('role/{id}/permission');

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

    allocatePermission (id , data , success , error) {

        return request(allocatePermission.replace('{id}' , id) , 'post' , data , success , error)
    } ,

    permission (id , success , error) {
        return request(permission.replace('{id}' , id) , 'get' , null , success , error)
    } ,

};