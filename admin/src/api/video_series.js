const index = genUrl('video_series');
const store = genUrl('video_series');
const update = genUrl('video_series/{id}');
const show = genUrl('video_series/{id}');
const destroy = genUrl('video_series/{id}');
const destroyAll = genUrl('destroy_all_video_series');
const search = genUrl('search_video_series');

export default {
    index (query) {
        return Http.get(index , query);
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

    search (query) {
        return Http.get(search , query);
    } ,

};
