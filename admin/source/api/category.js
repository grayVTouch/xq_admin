const index = genUrl('category');
const store = genUrl('category');
const localUpdate = genUrl('category/{id}');
const update = genUrl('category/{id}');
const show = genUrl('category/{id}');
const destroy = genUrl('category/{id}');
const destroyAll = genUrl('destroy_all_category');
const categoryExcludeSelfAndChildren = genUrl('category/{id}/all');
const searchByModuleId = genUrl('search_category_by_module_id');

export default {
    index (success , error) {
        return request(index , 'get' , null , success , error);
    } ,

    searchByModuleId (moduleId , success , error) {
        return request(searchByModuleId , 'get' , {
            module_id: moduleId
        } , success , error);
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

    categoryExcludeSelfAndChildren (id , success , error) {
        return request(categoryExcludeSelfAndChildren.replace('{id}' , id) , 'get' , null , success , error)
    } ,

};