const index = genUrl('video_subject');
const store = genUrl('video_subject');
const update = genUrl('video_subject/{id}');
const show = genUrl('video_subject/{id}');
const destroy = genUrl('video_subject/{id}');
const destroyAll = genUrl('destroy_all_video_subject');
const search = genUrl('search_video_subject');
const destroyTag = genUrl('destroy_video_subject_tag');

export default {
    index (data , success , error) {
        return request(index , 'get' , data , success , error);
    } ,

    localUpdate (id , data , success , error) {
        const url = localUpdate.replace('{id}' , id);
        return request(url , 'patch' , data , success , error)
    } ,

    update (id , data , success , error) {
        const url = update.replace('{id}' , id);
        return request(url , 'put' , data , success , error)
    } ,

    store (data , success , error) {
        return request(store , 'post' , data , success , error)
    } ,

    show (id , success , error) {
        const url = show.replace('{id}' , id);
        return request(show , 'get' , null , success , error)
    } ,

    destroy (id , success , error) {
        const url = destroy.replace('{id}' , id);
        return request(url , 'delete' , null , success , error)
    } ,

    destroyAll (idList , success , error) {
        return request(destroyAll , 'delete' , {
            ids: G.jsonEncode(idList)
        } , success , error)
    } ,

    search (data , success , error) {
        return request(search , 'get' , data , success , error);
    } ,

    destroyTag (data , success , error) {
        return request(destroyTag , 'delete' , data , success , error);
    } ,
};