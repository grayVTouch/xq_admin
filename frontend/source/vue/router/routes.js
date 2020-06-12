import login from '../view/login/login.vue';
import index from '../view/index/index.vue';
import pannel from '../view/pannel/pannel.vue';
import indexForAdminPermission from '../view/admin_permission/index.vue';
import indexForRole from '../view/role/index.vue';
import NotFoundView from '../view/error/404.vue';

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
        component: indexForAdminPermission
    } ,

    {
        path: '/role/index' ,
        component: indexForRole
    } ,

    {
        name: '404' ,
        path: '/404' ,
        component: NotFoundView
    } ,
]