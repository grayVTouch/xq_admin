/**
 * @author running
 */

export default new Vuex.Store({
    state: {
        // 当前登录用户
        user: {} ,

        context: TopContext ,

        business: TopContext.business ,

        position: [] ,
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