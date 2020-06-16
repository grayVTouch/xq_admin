import NotFoundView from '../view/error/404.vue';
import login from '../view/login/login.vue';
import index from '../view/index/index.vue';
import pannel from '../view/pannel/pannel.vue';
import indexForAdminPermission from '../view/admin_permission/index.vue';
import indexForRole from '../view/role/index.vue';
import indexForModule from '../view/module/index.vue';
import indexForTag from '../view/tag/index.vue';
import indexForCategory from '../view/category/index.vue';
import indexForSubject from '../view/subject/index.vue';
import indexForImageSubject from '../view/image_subject/index.vue';


export default [
    {
        name: '404' ,
        path: '/404' ,
        component: NotFoundView
    } ,
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
        path: '/module/index' ,
        component: indexForModule
    } ,
    {
        path: '/tag/index' ,
        component: indexForTag
    } ,
    {
        path: '/category/index' ,
        component: indexForCategory
    } ,

    {
        path: '/subject/index' ,
        component: indexForSubject
    } ,

    {
        path: '/image_subject/index' ,
        component: indexForImageSubject
    } ,


]