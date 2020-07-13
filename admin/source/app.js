
import './asset/css/base.css';
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
import './asset/js/directive.js';
import './asset/js/iview.js';

import './asset/js/my_view.js';

import './asset/js/my_plugin.js';
import router from './vue/router';
import app from './app.vue';

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
    router ,
    template: '<app />' ,
    components: {
        app ,
    } ,
});