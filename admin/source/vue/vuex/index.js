/**
 * @author running
 */

export default new Vuex.Store({
    state: {
        // 当前登录用户
        user: {} ,
        // 当前登录用户权限
        permission: [] ,

        permissionWithStructure: [] ,

        context: TopContext ,

        business: TopContext.business ,

        areaDomHInContent: 0 ,
    } ,
    mutations: {
        user (state , payload) {
            state.user = payload;
        } ,

        permission (state , payload) {
            state.permission = payload;
        } ,

        permissionWithStructure (state , payload) {
            state.permissionWithStructure = payload;
        } ,

        areaDomHInContent (state , payload) {
            state.areaDomHInContent = payload;
        } ,
    } ,
    actions: {
        user (context , payload) {
            context.commit('user' , payload);
        } ,

        permission (context , payload) {
            context.commit('permission' , payload);
        } ,

        permissionWithStructure (context , payload) {
            context.commit('permissionWithStructure' , payload);
        } ,

        areaDomHInContent (context , payload) {
            context.commit('areaDomHInContent' , payload);
        } ,
    } ,
});