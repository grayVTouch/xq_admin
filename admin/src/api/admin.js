const info = genUrl('info');
const search = genUrl('search_admin');

const index = genUrl('admin');
const store = genUrl('admin');
const update = genUrl('admin/{id}');
const show = genUrl('admin/{id}');
const destroy = genUrl('admin/{id}');
const destroyAll = genUrl('destroy_all_admin');

export default {

    index (param) {
        return Http.get(index , param);
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

    info () {
        return Http.get(info);
    } ,

    search (value) {
        return Http.get(search , {
            value
        });
    } ,
};
