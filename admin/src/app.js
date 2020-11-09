
import '@asset/css/vars.css';
import '@asset/css/base.css';
import '@asset/css/iview_reset.css';

/**
 * **************************
 * 注意：以下加载有严格顺序
 * **************************
 */
//
import '@bootstrap/my_plugin.js';

import '@config/context.js';
import '@util/common.js';
import '@util/request.js';
import '@util/api.js';
//
import '@bootstrap/vue.js';
import '@vue/mixin/mixin.js';
import '@vue/directive/directive.js';
import '@bootstrap/iview.js';

import '@bootstrap/my_view.js';
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
