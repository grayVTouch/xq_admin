
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

        modal (action , title , content = '' , onOk , merge = {}) {
            return this.$Modal[action]({
                title ,
                content ,
                onOk ,
                ...merge ,
            });
        } ,

        message (action , content = '' , merge = {}) {
            return this.$Message[action]({
                background: true ,
                content ,
                ...merge ,
            });
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


        select (key , val) {
            if (!G.isValid(val)) {
                return this.val.select[key];
            }
            this.val.select[key] = val;
            this.val.select = {...this.val.select , ...{[key]: val}};
        } ,


    }
});
