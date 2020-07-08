const login = `${TopContext.api}/login`;
const register = `${TopContext.api}/register`;
const info = `${TopContext.api}/user_info`;
const updatePassword = `${TopContext.api}/user/update_password`;
const lessHistory = `${TopContext.api}/less_history`;
const histories = `${TopContext.api}/histories`;
const collectionGroup = `${TopContext.api}/user/collection_group`;
const createAndJoinCollectionGroup = `${TopContext.api}/user/create_and_join_collection_group`;
const joinCollectionGroup = `${TopContext.api}/user/join_collection_group`;
const destroyCollectionGroup = `${TopContext.api}/user/{collection_group_id}/destroy_collection_group`;
const destroyAllCollectionGroup = `${TopContext.api}/user/destroy_all_collection_group`;
const praiseHandle = `${TopContext.api}/user/praise_handle`;
const collectionHandle = `${TopContext.api}/user/collection_handle`;
const record = `${TopContext.api}/user/record`;

export default {
    login (data , success , error) {
        return request(login , 'post' , data , success , error);
    } ,

    register (data , success , error) {
        return request(register , 'post' , data , success , error);
    } ,

    info (success , error) {
        return request(info , 'get' , null , success , error);
    } ,

    updatePassword (data , success , error) {
        return request(updatePassword , 'patch' , data , success , error);
    } ,

    lessHistory (data , success , error) {
        return request(lessHistory , 'get' , data , success , error);
    } ,

    histories (data , success , error) {
        return request(histories , 'get' , data , success , error);
    } ,

    praiseHandle (data , success , error) {
        return request(praiseHandle , 'post' , data , success , error);
    } ,

    collectionHandle (data , success , error) {
        return request(collectionHandle , 'post' , data , success , error);
    } ,

    record (data , success , error) {
        return request(record , 'post' , data , success , error);
    } ,

    createAndJoinCollectionGroup (data , success , error) {
        return request(createAndJoinCollectionGroup , 'post' , data , success , error);
    } ,

    joinCollectionGroup (data , success , error) {
        return request(joinCollectionGroup , 'post' , data , success , error);
    } ,

    collectionGroup (data , success , error) {
        return request(collectionGroup , 'get' , data , success , error);
    } ,

    destroyCollectionGroup (collectionGroupId , success , error) {
        return request(destroyCollectionGroup.replace('' , collectionGroupId) , 'delete' , null , success , error);
    } ,

    destroyAllCollectionGroup (collectionGroupIds , success , error) {
        return request(destroyAllCollectionGroup , 'delete' , {
            collection_group_ids: G.jsonEncode(collectionGroupIds)
        } , success , error);
    } ,


};