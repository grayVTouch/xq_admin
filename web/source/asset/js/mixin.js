
import store from '../../vue/vuex';

/**
 * ****************
 * 全局混入
 * ****************
 */
Vue.mixin({

    store ,

    data () {
        return {
            val: {
                pending: {} ,
                error: {} ,
                select: {} ,
            } ,
        };
    } ,

    methods: {

        isValid (val) {
            return G.isValid(val);
        } ,

        push (...args) {
            this.$router.push.apply(this.$router , args);
        } ,

        state (key) {
            return this.$store.state[key];
        } ,

        message (msg , option = {}) {
            return Prompt.alert(msg , option);
        } ,

        link (url , type = '_blank') {
            window.open(url , type);
        } ,

        pending (key , val) {
            if (!G.isValid(val)) {
                return this.val.pending[key];
            }
            this.val.pending[key] = val;
            this.val.pending = {...this.val.pending , ...{[key]: val}};
        } ,

        error (error = {} , clear = true) {
            if (clear) {
                this.val.error = {...error};
            }
            this.val.error = {...this.val.error , ...error};
        } ,

        _val (key , val) {
            if (!G.isValid(val)) {
                return this.val[key];
            }
            this.val = {...this.val , ...{[key]: val}};
        } ,

        errorHandle (data) {

        } ,

        successHandle (callback) {

        } ,
    }
});
