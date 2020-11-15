
import permissions from '@permission/permission.js';

/**
 * @author running
 */

export default new Vuex.Store({
    state: {
        // 当前登录用户
        user: {} ,

        // 当前登录用户权限
        myPermission: [] ,

        // 带数据结构的权限
        myPermissionWithStructure: [] ,

        // 带数据结构的菜单列表
        permissions ,

        context: TopContext ,

        business: TopContext.business ,

        areaDomHInContent: 0 ,

        // 侧边栏
        slidebar: G.cookie.exists('slidebar') ? G.cookie.get('slidebar') : 'horizontal' ,

        // 当前路由
        currentRoute: {} ,

        // 顶级路由
        topRoute: {} ,

        // 当前位置
        positions: [] ,
    } ,
    mutations: {
        user (state , payload) {
            state.user = payload;
        } ,

        myPermission (state , payload) {
            state.myPermission = payload;
        } ,

        myPermissionWithStructure (state , payload) {
            state.myPermissionWithStructure = payload;
        } ,

        areaDomHInContent (state , payload) {
            state.areaDomHInContent = payload;
        } ,

        permissions (state , payload) {
            state.permissions = payload;
        } ,

        slidebar (state , payload) {
            state.slidebar = payload;
        } ,

        topRoute (state , payload) {
            state.topRoute = payload;
        } ,

        currentRoute (state , payload) {
            state.currentRoute = payload;
        } ,

        positions (state , payload) {
            state.positions = payload;
        } ,
    } ,
    actions: {
        user (context , payload) {
            context.commit('user' , payload);
        } ,

        myPermission (context , payload) {
            context.commit('myPermission' , payload);
        } ,

        permissions (context , payload) {
            context.commit('permissions' , payload);
        } ,

        myPermissionWithStructure (context , payload) {
            context.commit('myPermissionWithStructure' , payload);
        } ,

        areaDomHInContent (context , payload) {
            context.commit('areaDomHInContent' , payload);
        } ,

        slidebar (context , payload) {
            context.commit('slidebar' , payload);
        } ,

        topRoute (context , payload) {
            context.commit('topRoute' , payload);
        } ,

        currentRoute (context , payload) {
            context.commit('currentRoute' , payload);
        } ,

        positions (context , payload) {
            context.commit('positions' , payload);
        } ,

    } ,
});
