
const destroy = genUrl('video_subtitle/{id}');
const destroyAll = genUrl('destroy_all_video_subtitle');

export default {
    destroy (id , success , error) {
        const url = destroy.replace('{id}' , id);
        return request(url , 'delete' , null , success , error)
    } ,

    destroyAll (ids , success , error) {
        return request(destroyAll , 'delete' , {
            ids: G.jsonEncode(ids)
        } , success , error)
    } ,

};