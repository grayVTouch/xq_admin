const index = genUrl('tag');
const store = genUrl('tag');
const update = genUrl('tag/{id}');
const show = genUrl('tag/{id}');
const destroy = genUrl('tag/{id}');
const destroyAll = genUrl('destroy_all_tag');
const search = genUrl('search_tag');
const topByModuleId = genUrl('top_by_module_id');
const findOrCreateTag = genUrl('find_or_create_tag');

export default {
    index (param) {
        return Http.get(index , param);
    } ,

    localUpdate (id , param) {
        return Http.patch(localUpdate.replace('{id}' , id) , param);
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
        return Http.delete(destroyAll , null , {
            ids: G.jsonEncode(ids)
        });
    } ,

    search (value) {
        return Http.get(search , {
            value ,
        });
    } ,

    topByModuleId (moduleId) {
        return Http.get(topByModuleId , {
            module_id: moduleId
        });
    } ,

    findOrCreateTag (data) {
        return Http.post(findOrCreateTag , null , data)
    } ,

};
