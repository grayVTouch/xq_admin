import Vue from 'vue';
import VueRouter from 'vue-router';

import app from './app.vue';
import home from './vue/view/index/home.vue';
import index from './vue/view/index/index.vue';

Vue.use(VueRouter);



const vueRouter = new VueRouter({
    routes: [
        {
            path: '/' ,
            component: home ,
            children: [
                {
                    path: 'index' ,
                    component: index ,
                }
            ] ,
        }
    ]
});

new Vue({
    el: '#app' ,
    router: vueRouter ,
    template: '<app></app>' ,
    components: {
        app ,
    } ,
});