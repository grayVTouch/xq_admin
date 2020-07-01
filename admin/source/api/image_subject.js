const index = genUrl('image_subject');
const store = genUrl('image_subject');
const update = genUrl('image_subject/{id}');
const show = genUrl('image_subject/{id}');
const destroy = genUrl('image_subject/{id}');
const destroyAll = genUrl('destroy_all_image_subject');
const destroyAllImageForImageSubject = genUrl('destroy_all_image_for_image_subject');
const destroyTag = genUrl('image_subject/{image_subject_id}/destroy/{tag_id}');

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

    destroyTag (imageSubjectId , tagId , success , error) {
        let url = destroyTag.replace('{image_subject_id}' , imageSubjectId);
            url = url.replace('{tag_id}' , tagId);
            console.log(url , imageSubjectId , tagId);
        return request(url , 'delete' , null , success , error)
    } ,


};