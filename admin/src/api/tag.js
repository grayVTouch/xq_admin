const index = genUrl('tag');
const store = genUrl('tag');
const update = genUrl('tag/{id}');
const show = genUrl('tag/{id}');
const destroy = genUrl('tag/{id}');
const destroyAll = genUrl('destroy_all_tag');
const search = genUrl('search_tag');
const findOrCreateTag = genUrl('find_or_create_tag');

export default {
    index (query) {
        return Http.get(index , query);
    } ,

    localUpdate (id , query) {
        return Http.patch(localUpdate.replace('{id}' , id) , query);
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

    top (query) {
        return Http.get(`${TopContext.api}/top_tags` , query);
    } ,

    findOrCreateTag (data) {
        return Http.post(findOrCreateTag , null , data)
    } ,

};
