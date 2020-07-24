const login = `${TopContext.api}/login`;
const register = `${TopContext.api}/register`;
const info = `${TopContext.api}/user_info`;
const updatePassword = `${TopContext.api}/user/update_password`;
const lessHistory = `${TopContext.api}/less_history`;
const history = `${TopContext.api}/history`;
const collectionGroup = `${TopContext.api}/user/collection_group`;
const collectionGroupByUserId = `${TopContext.api}/user/{user_id}/collection_group`;
const collections = `${TopContext.api}/user/collections`;
const collectionGroupWithJudge = `${TopContext.api}/user/collection_group_with_judge`;
const createAndJoinCollectionGroup = `${TopContext.api}/user/create_and_join_collection_group`;
const createCollectionGroup = `${TopContext.api}/user/create_collection_group`;
const joinCollectionGroup = `${TopContext.api}/user/join_collection_group`;
const destroyCollectionGroup = `${TopContext.api}/user/destroy_collection_group`;
const destroyAllCollectionGroup = `${TopContext.api}/user/destroy_all_collection_group`;
const praiseHandle = `${TopContext.api}/user/praise_handle`;
const collectionHandle = `${TopContext.api}/user/collection_handle`;
const record = `${TopContext.api}/user/record`;
const lessCollectionGroupWithCollection = `${TopContext.api}/less_collection_group_with_collection`;
const update = `${TopContext.api}/user/update`;
const updatePasswordInLogged = `${TopContext.api}/user/update_password_in_logged`;
const destroyHistory = `${TopContext.api}/user/destroy_history`;
const destroyCollection = `${TopContext.api}/user/destroy_collection`;
const updateCollectionGroup = `${TopContext.api}/user/update_collection_group`;
const focusHandle = `${TopContext.api}/user/focus_handle`;
const show = `${TopContext.api}/user/{user_id}/show`;
const myFocusUser = `${TopContext.api}/user/{user_id}/my_focus_user`;
const focusMeUser = `${TopContext.api}/user/{user_id}/focus_me_user`;
const localUpdate = `${TopContext.api}/user/{user_id}/update`;
const collectionGroupInfo = `${TopContext.api}/user/{collection_group_id}/collection_group_info`;

export default {
    login(data, success, error) {
        return request(login, 'post', data, success, error);
    },

    register(data, success, error) {
        return request(register, 'post', data, success, error);
    },

    info(success, error) {
        return request(info, 'get', null, success, error);
    },

    updatePassword(data, success, error) {
        return request(updatePassword, 'patch', data, success, error);
    },

    lessHistory(data, success, error) {
        return request(lessHistory, 'get', data, success, error);
    },

    history(data, success, error) {
        return request(history, 'get', data, success, error);
    },

    praiseHandle(data, success, error) {
        return request(praiseHandle, 'post', data, success, error);
    },

    collectionHandle(data, success, error) {
        return request(collectionHandle, 'post', data, success, error);
    },

    record(data, success, error) {
        return request(record, 'post', data, success, error);
    },

    createAndJoinCollectionGroup(data, success, error) {
        return request(createAndJoinCollectionGroup, 'post', data, success, error);
    },

    joinCollectionGroup(data, success, error) {
        return request(joinCollectionGroup, 'post', data, success, error);
    },

    collectionGroup(data, success, error) {
        return request(collectionGroup, 'get', data, success, error);
    },

    collectionGroupWithJudge(data, success, error) {
        return request(collectionGroupWithJudge, 'get', data, success, error);
    },

    destroyCollectionGroup(data, success, error) {
        return request(destroyCollectionGroup, 'delete', data, success, error);
    },

    destroyAllCollectionGroup(collectionGroupIds, success, error) {
        return request(destroyAllCollectionGroup, 'delete', {
            collection_group_ids: G.jsonEncode(collectionGroupIds)
        }, success, error);
    },

    lessCollectionGroupWithCollection(data, success, error) {
        return request(lessCollectionGroupWithCollection, 'get', data, success, error);
    },

    update(data, success, error) {
        return request(update, 'put', data, success, error);
    },

    updatePasswordInLogged(data, success, error) {
        return request(updatePasswordInLogged, 'patch', data, success, error);
    },

    destroyHistory(ids, success, error) {
        return request(destroyHistory, 'delete', {
            history_ids: G.jsonEncode(ids)
        }, success, error);
    },

    collections(data, success, error) {
        return request(collections, 'get', data, success, error);
    },

    destroyCollections(data, success, error) {
        return request(collections, 'delete', data, success, error);
    },

    createCollectionGroup(data, success, error) {
        return request(createCollectionGroup, 'post', data, success, error);
    },

    destroyCollection(data, success, error) {
        return request(destroyCollection, 'delete', data, success, error);
    },

    updateCollectionGroup (data, success, error) {
        return request(updateCollectionGroup, 'patch', data, success, error);
    },

    focusHandle (data, success, error) {
        return request(focusHandle, 'post', data, success, error);
    } ,

    show (userId, success, error) {
        return request(show.replace('{user_id}' , userId), 'get', null , success, error);
    } ,

    focusMeUser (userId , data , success, error) {
        return request(focusMeUser.replace('{user_id}' , userId), 'get', data, success, error);
    } ,

    myFocusUser (userId , data , success, error) {
        return request(myFocusUser.replace('{user_id}' , userId), 'get', data, success, error);
    } ,

    collectionGroupByUserId (userId , data , success, error) {
        return request(collectionGroupByUserId.replace('{user_id}' , userId), 'get', data, success, error);
    } ,

    localUpdate (userId , data , success, error) {
        return request(localUpdate.replace('{user_id}' , userId), 'patch', data, success, error);
    } ,


    collectionGroupInfo (collectionGroupId , success, error) {
        return request(collectionGroupInfo.replace('{collection_group_id}' , collectionGroupId), 'get', null, success, error);
    } ,
};