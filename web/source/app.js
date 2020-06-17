
import './asset/css/iview.css';

/**
 * **************************
 * 注意：以下加载有严格顺序
 * **************************
 */
import './asset/js/context.js';
import './asset/js/common.js';
import './asset/js/api.js';

import './asset/js/vue.js';
import './asset/js/mixin.js';
import './asset/js/iview.js';
import './asset/js/my_view.js';
import './asset/js/my_plugin.js';

import app from './app.vue';

console.log('入口 1');


const debug = true;

Vue.config.debug = debug;

Vue.config.devtools = debug;

Vue.config.productionTip = debug;

/**
 * ****************
 * vue 实例
 * ****************
 */
new Vue({
    el: '#app' ,
    template: '<app />' ,
    components: {
        app ,
    } ,
});