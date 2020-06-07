/*** css 通用依赖 ***/
import './plugin/Run/base.css';
import './plugin/Run/color.css';
import './plugin/Run/font.css';
import './plugin/Run/button.css';
import './plugin/Run/title.css';
import './plugin/Run/box.css';
import './plugin/Run/page.css';

/*** js 依赖 ***/
import './asset/js/init.js';
import './asset/js/common.js';
import './asset/js/api.js';


import router from './vue/router';
import store from './vue/vuex';
import app from './app.vue';


import 'view-design/dist/styles/iview.css';
import {
    Button ,
} from 'view-design';

import loading from './vue/view/public/loading.vue';

Vue.component('Button' , Button);
Vue.component('my-loading' , loading);

Vue.mixin({
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
    }
});

new Vue({
    el: '#app' ,
    router ,
    store ,
    template: '<app />' ,
    components: {
        app ,
    } ,
});