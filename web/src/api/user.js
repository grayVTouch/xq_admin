const login = `${TopContext.api}/login`;
const info = `${TopContext.api}/user_info`;
const history = `${TopContext.api}/history`;
const collectionGroup = `${TopContext.api}/user/collection_group`;
const collectionGroupByUserId = `${TopContext.api}/user/{user_id}/collection_group`;
const collections = `${TopContext.api}/user/collections`;
const createAndJoinCollectionGroup = `${TopContext.api}/user/create_and_join_collection_group`;
const createCollectionGroup = `${TopContext.api}/user/create_collection_group`;
const joinCollectionGroup = `${TopContext.api}/user/join_collection_group`;
const destroyCollectionGroup = `${TopContext.api}/user/destroy_collection_group`;
const destroyAllCollectionGroup = `${TopContext.api}/user/destroy_all_collection_group`;
const update = `${TopContext.api}/update_user`;
const updatePasswordInLogged = `${TopContext.api}/user/update_password_in_logged`;
const destroyHistory = `${TopContext.api}/user/destroy_history`;
const destroyCollection = `${TopContext.api}/user/destroy_collection`;
const updateCollectionGroup = `${TopContext.api}/user/update_collection_group`;
const show = `${TopContext.api}/user/{user_id}/show`;
const myFocusUser = `${TopContext.api}/user/{user_id}/my_focus_user`;
const focusMeUser = `${TopContext.api}/user/{user_id}/focus_me_user`;
const localUpdate = `${TopContext.api}/update_user`;
const collectionGroupInfo = `${TopContext.api}/user/{collection_group_id}/collection_group_info`;

export default {
    login(query , data) {
        return Http.post(login, query , data);
    },

    register (query , data) {
        return Http.post(`${TopContext.api}/register`, query , data);
    },

    info(success, error) {
        return request(info, 'get', null);
    },

    updatePassword (query , data) {
        return Http.patch( `${TopContext.api}/user/update_password` , query , data);
    },

    lessHistory (query) {
        return Http.get(`${TopContext.api}/less_history`, query);
    },

    history(data) {
        return request(history, 'get', data);
    },

    praiseHandle(query , data) {
        return Http.post(`${TopContext.api}/user/praise_handle`, query , data);
    },

    collectionHandle(query , data) {
        return Http.post( `${TopContext.api}/user/collection_handle` , query , data);
    },

    // 记录用户行为
    record (query , data) {
        return Http.post(`${TopContext.api}/user/record` , query , data);
    },

    createAndJoinCollectionGroup(data) {
        return request(createAndJoinCollectionGroup, 'post', data);
    },

    joinCollectionGroup(data) {
        return request(joinCollectionGroup, 'post', data);
    },

    collectionGroup(data) {
        return request(collectionGroup, 'get', data);
    },

    collectionGroupWithJudge (query) {
        return Http.get(`${TopContext.api}/user/collection_group_with_judge`, query);
    },

    destroyCollectionGroup(data) {
        return request(destroyCollectionGroup, 'delete', data);
    },

    destroyAllCollectionGroup(collectionGroupIds) {
        return request(destroyAllCollectionGroup, 'delete', {
            collection_group_ids: G.jsonEncode(collectionGroupIds)
        });
    },

    lessCollectionGroupWithCollection(query) {
        return Http.get(`${TopContext.api}/less_collection_group_with_collection` , query);
    },

    update (data) {
        return request(update, 'put', data);
    },

    updatePasswordInLogged(data) {
        return request(updatePasswordInLogged, 'patch', data);
    },

    destroyHistory(ids) {
        return request(destroyHistory, 'delete', {
            history_ids: G.jsonEncode(ids)
        });
    },

    collections(data) {
        return request(collections, 'get', data);
    },

    destroyCollections(data) {
        return request(collections, 'delete', data);
    },

    createCollectionGroup(data) {
        return request(createCollectionGroup, 'post', data);
    },

    destroyCollection(data) {
        return request(destroyCollection, 'delete', data);
    },

    updateCollectionGroup (data) {
        return request(updateCollectionGroup, 'patch', data);
    },

    focusHandle (query , data) {
        return Http.post(`${TopContext.api}/user/focus_handle` , query , data);
    } ,

    show (userId) {
        return request(show.replace('{user_id}' , userId), 'get', null);
    } ,

    focusMeUser (userId , query) {
        return Http.get(`${TopContext.api}/user/${userId}/focus_me_user` , query);
    } ,

    myFocusUser (userId , data) {
        return request(myFocusUser.replace('{user_id}' , userId), 'get', data);
    } ,

    collectionGroupByUserId (userId , data) {
        return request(collectionGroupByUserId.replace('{user_id}' , userId), 'get', data);
    } ,

    localUpdate (data) {
        return request(localUpdate , 'patch', data);
    } ,


    collectionGroupInfo (collectionGroupId) {
        return request(collectionGroupInfo.replace('{collection_group_id}' , collectionGroupId), 'get', null);
    } ,
};
