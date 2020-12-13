const index = genUrl('disk');
const store = genUrl('disk');
const localUpdate = genUrl('disk/{id}');
const update = genUrl('disk/{id}');
const show = genUrl('disk/{id}');
const destroy = genUrl('disk/{id}');
const destroyAll = genUrl('destroy_all_disk');
const linkDisk = genUrl('link_disk');

export default {
    index (param) {
        return Http.get(index , param);
    } ,

    localUpdate (id , data) {
        return Http.patch(localUpdate.replace('{id}' , id) , null , data)
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
        })
    } ,

    linkDisk (ids) {
        return Http.post(linkDisk , null , {
            ids: G.jsonEncode(ids)
        });
    } ,

};
