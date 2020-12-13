const index = genUrl('module');
const store = genUrl('module');
const update = genUrl('module/{id}');
const localUpdate = genUrl('module/{id}');
const show = genUrl('module/{id}');
const destroy = genUrl('module/{id}');
const destroyAll = genUrl('destroy_all_module');
const all = genUrl('get_all_module');

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

    all () {
        return Http.get(all);
    } ,
};
