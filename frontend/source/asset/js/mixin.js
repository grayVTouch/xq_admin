
import router from '../../vue/router';
import store from '../../vue/vuex';

/**
 * ****************
 * 全局混入
 * ****************
 */
Vue.mixin({
    router ,

    store ,

    methods: {
        isValid (val) {
            return G.isValid(val);
        } ,

        getClass (val) {
            return G.isValid(val) ? 'error' : '';
        } ,

        push (...args) {
            this.$router.push.apply(this.$router , args);
        } ,

        notice (title , desc) {
            return this.$Notice.open({
                title ,
                desc ,
            });
        } ,

        state (key) {
            return this.$store.state[key];
        } ,

        modal (action , config = {}) {
            return this.$Modal[action](config);
        } ,

    }
});
