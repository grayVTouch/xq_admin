import login from '../view/login/login.vue';
import index from '../view/index/index.vue';
import pannel from '../view/pannel/pannel.vue';
import listForPermission from '../view/permission/index.vue';

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
    } ,

    {
        path: '/pannel' ,
        component: pannel
    } ,

    {
        path: '/admin_permission/list' ,
        component: listForPermission
    } ,
]