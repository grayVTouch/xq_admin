const info = genUrl('info');
const search = genUrl('search_admin');

const index = genUrl('admin');
const store = genUrl('admin');
const update = genUrl('admin/{id}');
const show = genUrl('admin/{id}');
const destroy = genUrl('admin/{id}');
const destroyAll = genUrl('destroy_all_admin');

export default {
    info () {
        return request(info , 'get');
    } ,

    search (value) {
        return request(search , 'get' , {value});
    } ,

    index (param) {
        return request(index , 'get' , param);
    } ,

    update (id , data) {
        return request(update.replace('{id}' , id) , 'put' , null , data);
    } ,

    store (data) {
        return request(store , 'post' , null , data);
    } ,

    show (id) {
        return request(show.replace('{id}' , id) , 'get');
    } ,

    destroy (id) {
        return request(destroy.replace('{id}' , id) , 'delete');
    } ,

    destroyAll (ids) {
        return request(destroyAll , 'delete' , null , {
            ids: G.jsonEncode(ids) ,
        });
    } ,
};
