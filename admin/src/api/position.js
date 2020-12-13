const index = genUrl('position');
const store = genUrl('position');
const update = genUrl('position/{id}');
const show = genUrl('position/{id}');
const destroy = genUrl('position/{id}');
const destroyAll = genUrl('destroy_all_position');
const all = genUrl('get_all_position');
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

    all () {
        return Http.get(all);
    } ,
};
