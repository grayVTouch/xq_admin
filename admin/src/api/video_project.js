const index = genUrl('video_project');
const store = genUrl('video_project');
const update = genUrl('video_project/{id}');
const show = genUrl('video_project/{id}');
const destroy = genUrl('video_project/{id}');
const destroyAll = genUrl('destroy_all_video_project');
const search = genUrl('search_video_project');
const destroyTag = genUrl('destroy_video_project_tag');

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

    search (param) {
        return Http.get(search , param);
    } ,

    destroyTag (data) {
        return Http.delete(destroyTag , null , data);
    } ,
};
