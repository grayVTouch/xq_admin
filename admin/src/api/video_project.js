const index = genUrl('video_project');
const store = genUrl('video_project');
const update = genUrl('video_project/{id}');
const show = genUrl('video_project/{id}');
const destroy = genUrl('video_project/{id}');
const destroyAll = genUrl('destroy_all_video_project');
const search = genUrl('search_video_project');
const destroyTag = genUrl('destroy_video_project_tag');

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
        return request(show.replace('{id}' , id) , 'get' , null , success , error)
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
