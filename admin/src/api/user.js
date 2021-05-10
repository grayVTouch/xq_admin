const search = genUrl('search_user');
const index = genUrl('user');
const store = genUrl('user');
const update = genUrl('user/{id}');
const show = genUrl('user/{id}');
const destroy = genUrl('user/{id}');
const destroyAll = genUrl('destroy_all_user');

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

    search (query) {
        return Http.get(search , query);
    } ,
};
