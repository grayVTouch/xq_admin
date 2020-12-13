const index = genUrl('image_subject');
const store = genUrl('image_subject');
const update = genUrl('image_subject/{id}');
const show = genUrl('image_subject/{id}');
const destroy = genUrl('image_subject/{id}');
const destroyAll = genUrl('destroy_all_image_subject');
const search = genUrl('search_image_subject');

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

    search (param) {
        return Http.get(search , param);
    } ,

};
