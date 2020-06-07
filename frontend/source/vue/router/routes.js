import login from '../view/login/login.vue';
import index from '../view/index/index.vue';

export default [
    {
        name: 'login' ,
        path: '/login' ,
        component: login
    } ,
    {
        name: 'home' ,
        path: '/' ,
        component: index
    }
]