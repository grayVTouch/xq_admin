const index = genUrl('category');
const store = genUrl('category');
const localUpdate = genUrl('category/{id}');
const update = genUrl('category/{id}');
const show = genUrl('category/{id}');
const destroy = genUrl('category/{id}');
const destroyAll = genUrl('destroy_all_category');
const categoryExcludeSelfAndChildren = genUrl('category/{id}/all');
const searchByModuleId = genUrl('search_category_by_module_id');
const search = genUrl('search_category');

export default {

    index (query) {
        return Http.get(index , query);
    } ,

    localUpdate (id , data) {
        return Http.patch(localUpdate.replace('{id}' , id) , null , data);
    } ,

    update (id , data) {
        return Http.put(update.replace('{id}' , id) , null , data);
    } ,

    store (data) {
        return Http.post(store , null , data);
    } ,

    show (id) {
        return Http.get(show.replace('{id}' , id));
    } ,

    destroy (id) {
        return Http.delete(destroy.replace('{id}' , id));
    } ,

    destroyAll (ids) {
        return Http.delete(destroyAll , null ,{
            ids: G.jsonEncode(ids)
        });
    } ,

    searchByModuleId (moduleId) {
        return Http.get(searchByModuleId , {
            module_id: moduleId
        });
    } ,

    search (query) {
        return Http.get(search , query);
    } ,

    categoryExcludeSelfAndChildren (id) {
        return Http.get(categoryExcludeSelfAndChildren.replace('{id}' , id));
    } ,

};
