
const destroy       = genUrl('video_subtitle/{id}');
const destroyAll    = genUrl('destroy_all_video_subtitle');

export default {
    destroy (id) {
        return Http.delete(destroy.replace('{id}' , id));
    } ,

    destroyAll (ids) {
        return Http.delete(destroyAll , null , {
            ids: G.jsonEncode(ids)
        });
    } ,

};
