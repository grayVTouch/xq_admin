import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

import '../../plugin/TouchFeedback/css/TouchFeedback.css';
import '../../plugin/Prompt/css/Prompt.css';
import '../../plugin/InfiniteClassification/css/InfiniteClassification.css';

import SmallJs from '../../plugin/SmallJs/SmallJs.js';
import TouchFeedback from '../../plugin/TouchFeedback/js/TouchFeedback.js';
import Prompt from '../../plugin/Prompt/js/Prompt.js';
import InfiniteClassification from '../../plugin/InfiniteClassification/js/InfiniteClassification.js';

Vue.use(Vuex);
Vue.use(VueRouter);

Object.assign(window , {
    Vue ,
    VueRouter ,
    Vuex ,
    topContext: {
        api: 'http://xq.test/api/v1' ,
        successCode: 200 ,
    } ,
    Prompt ,
    InfiniteClassification ,
    TouchFeedback
});
