/**
 * 同步加载
 */
import login from '@vue/view/login/login.vue';
import index from '@vue/view/index/index.vue';
import pannel from '@vue/view/pannel/pannel.vue';
import NotFoundView from '@vue/view/error/404.vue';

/**
 * 异步加载
 */
const indexForAdminPermission = () => import('../view/admin_permission/index.vue');
const indexForRole = () => import('../view/role/index.vue');
const indexForModule = () => import('../view/module/index.vue');
const indexForTag = () => import('../view/tag/index.vue');
const indexForCategory = () => import('../view/category/index.vue');
const indexForSubject = () => import('../view/subject/index.vue');
const indexForImageSubject = () => import('../view/image_subject/index.vue');
const indexForUser = () => import('../view/user/index.vue');
const indexForAdmin = () => import('../view/admin/index.vue');
const indexForPosition = () => import('../view/position/index.vue');
const indexForImageAtPosition = () => import('../view/image_at_position/index.vue');
const indexForNav = () => import('../view/nav/index.vue');
const indexForVideo = () => import('../view/video/index.vue');
const indexForVideoSeries = () => import('../view/video_series/index.vue');
const indexForVideoSubject = () => import('../view/video_subject/index.vue');
const indexForVideoCompany = () => import('../view/video_company/index.vue');
const indexForDisk = () => import('../view/disk/index.vue');


export default [
    {
        name: '404' ,
        path: '/404' ,
        component: NotFoundView ,
        async: false ,
    } ,
    {
        name: 'login' ,
        path: '/login' ,
        component: login ,
        async: false ,
    } ,
    {
        name: 'home' ,
        path: '/' ,
        component: index ,
        async: false ,
    } ,

    {
        path: '/pannel' ,
        component: pannel ,
        async: false ,
    } ,

    {
        path: '/admin_permission/index' ,
        component: indexForAdminPermission ,
        async: true ,
    } ,

    {
        path: '/role/index' ,
        component: indexForRole ,
        async: true ,
    } ,
    {
        path: '/module/index' ,
        component: indexForModule ,
        async: true ,
    } ,
    {
        path: '/tag/index' ,
        component: indexForTag ,
        async: true ,
    } ,
    {
        path: '/category/index' ,
        component: indexForCategory ,
        async: true ,
    } ,

    {
        path: '/subject/index' ,
        component: indexForSubject ,
        async: true ,
    } ,

    {
        path: '/image_subject/index' ,
        component: indexForImageSubject ,
        async: true ,
    } ,

    {
        path: '/user/index' ,
        component: indexForUser ,
        async: true ,
    } ,

    {
        path: '/admin/index' ,
        component: indexForAdmin ,
        async: true ,
    } ,

    {
        path: '/position/index' ,
        component: indexForPosition ,
        async: true ,
    } ,

    {
        path: '/image_at_position/index' ,
        component: indexForImageAtPosition ,
        async: true ,
    } ,

    {
        path: '/nav/index' ,
        component: indexForNav ,
        async: true ,
    } ,

    {
        path: '/video/index' ,
        component: indexForVideo ,
        async: true ,
    } ,
    {
        path: '/video_series/index' ,
        component: indexForVideoSeries ,
        async: true ,
    } ,
    {
        path: '/video_subject/index' ,
        component: indexForVideoSubject ,
        async: true ,
    } ,
    {
        path: '/video_company/index' ,
        component: indexForVideoCompany ,
        async: true ,
    } ,

    {
        path: '/disk/index' ,
        component: indexForDisk ,
        async: true ,
    } ,

];
