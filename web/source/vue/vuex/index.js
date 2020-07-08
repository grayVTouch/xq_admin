/**
 * @author running
 */

export default new Vuex.Store({
    state: {
        // 当前登录用户
        user: null ,

        context: TopContext ,

        business: TopContext.business ,

        position: [] ,

        // 用户登录后需要处理的相关回调函数
        loggedCallback: [] ,
    } ,
    mutations: {
        user (state , payload) {
            state.user = payload;
        } ,

        position (state , payload) {
            state.position = payload;
        } ,
    } ,
    actions: {
        user (context , payload) {
            context.commit('user' , payload);
        } ,

        position (state , payload) {
            state.commit('position' , payload);
        } ,
    } ,
});