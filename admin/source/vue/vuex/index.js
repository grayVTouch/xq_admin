
import position from '@asset/js/position.js';

/**
 * @author running
 */

export default new Vuex.Store({
    state: {
        // 当前登录用户
        user: {} ,
        // 当前登录用户权限
        permission: [] ,

        // 当前位置
        position ,

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

        position (state , payload) {
            state.position = payload;
        } ,
    } ,
    actions: {
        user (context , payload) {
            context.commit('user' , payload);
        } ,

        permission (context , payload) {
            context.commit('permission' , payload);
        } ,

        position (context , payload) {
            context.commit('position' , payload);
        } ,

        permissionWithStructure (context , payload) {
            context.commit('permissionWithStructure' , payload);
        } ,

        areaDomHInContent (context , payload) {
            context.commit('areaDomHInContent' , payload);
        } ,
    } ,
});
