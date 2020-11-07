
import '@asset/css/vars.css';
import '@asset/css/base.css';
import '@asset/css/iview.css';

/**
 * **************************
 * 注意：以下加载有严格顺序
 * **************************
 */
//
import './util/my_plugin.js';

import './util/context.js';
import './util/common.js';
import './util/request.js';
import './util/api.js';
//
import './util/vue.js';
import './util/mixin.js';
import './util/directive.js';
import './util/iview.js';

import './util/my_view.js';
//
import router from '@vue/router/index.js';
//
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
    render (h) {
        return h(app);
    }
});
