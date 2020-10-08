const index = genUrl('image_project');
const store = genUrl('image_project');
const update = genUrl('image_project/{id}');
const show = genUrl('image_project/{id}');
const destroy = genUrl('image_project/{id}');
const destroyAll = genUrl('destroy_all_image_project');
const destroyAllImageForImageSubject = genUrl('destroy_all_image_for_image_project');
const destroyTag = genUrl('destroy_image_project_tag');

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

    destroyAll (ids , success , error) {
        return request(destroyAll , 'delete' , {
            ids: G.jsonEncode(ids)
        } , success , error)
    } ,

    destroyAllImageForImageSubject (ids , success , error) {
        return request(destroyAllImageForImageSubject , 'delete' , {
            ids: G.jsonEncode(ids)
        } , success , error)
    } ,

    destroyTag (data , success , error) {
        return request(destroyTag , 'delete' , data , success , error)
    } ,


};
