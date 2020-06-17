/**
 * @author running
 */

export default new Vuex.Store({
    state: {
        // 当前登录用户
        user: {} ,
        context: TopContext ,

        business: TopContext.business ,
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
    } ,
});