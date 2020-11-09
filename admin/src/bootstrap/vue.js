import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

Vue.use(Vuex);
Vue.use(VueRouter);


// const isDebug_mode = process.env.NODE_ENV !== 'production';


window.Vue = Vue;
window.VueRouter = VueRouter;
window.Vuex = Vuex;