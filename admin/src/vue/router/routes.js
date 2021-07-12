/**
 * 同步加载
 */

/**
 * 异步加载
 */
const login = () => import('@vue/view/login/login.vue');
const index = () => import('@vue/view/index/index.vue');
const pannel = () => import('@vue/view/pannel/pannel.vue');
const notFound = () => import('@vue/view/error/404.vue');

const indexForAdminPermission = () => import('../view/admin_permission/index.vue');
const indexForRole = () => import('../view/role/index.vue');
const indexForModule = () => import('../view/module/index.vue');
const indexForTag = () => import('../view/tag/index.vue');
const indexForCategory = () => import('../view/category/index.vue');
const indexForImageSubject = () => import('../view/image_subject/index.vue');
const indexForImageProject = () => import('../view/image_project/index.vue');
const indexForUser = () => import('../view/user/index.vue');
const indexForAdmin = () => import('../view/admin/index.vue');
const indexForPosition = () => import('../view/position/index.vue');
const indexForImageAtPosition = () => import('../view/image_at_position/index.vue');
const indexForNav = () => import('../view/nav/index.vue');
const indexForVideo = () => import('../view/video/index.vue');
const indexForVideoSeries = () => import('../view/video_series/index.vue');
const indexForVideoSubject = () => import('../view/video_project/index.vue');
const indexForVideoCompany = () => import('../view/video_company/index.vue');
const indexForDisk = () => import('../view/disk/index.vue');


export default [
    {
        name: '404' ,
        path: '/404' ,
        component: notFound ,
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
        redirect: '/pannel' ,
        async: false ,
        children: [
            {
                path: 'pannel' ,
                component: () => import('@vue/view/pannel/pannel.vue') ,
            } ,
        ]
    } ,
    {
        path: '/admin' ,
        component: index ,
        children: [
            {
                path: 'index' ,
                component: () => import('@vue/view/admin/index.vue') ,
            }
        ] ,
    } ,
    {
        path: '/user' ,
        component: index ,
        children: [
            {
                path: 'index' ,
                component: () => import('@vue/view/user/index.vue') ,
            }
        ] ,
    } ,
    {
        path: '/image' ,
        component: index ,
        children: [
            {
                path: 'subject' ,
                component: () => import('@vue/view/image_subject/index.vue') ,
            } ,
            {
                path: 'project' ,
                component: () => import('@vue/view/image_project/index.vue') ,
            } ,
        ] ,
    } ,
    {
        path: '/video' ,
        component: index ,
        children: [
            {
                path: 'series' ,
                component: () => import('@vue/view/video_series/index.vue') ,
            } ,
            {
                path: 'company' ,
                component: () => import('@vue/view/video_company/index.vue') ,
            } ,
            {
                path: 'project' ,
                component: () => import('@vue/view/video_project/index.vue') ,
            } ,
            {
                path: 'index' ,
                component: () => import('@vue/view/video/index.vue') ,
            } ,
        ] ,
    } ,
    {
        path: '/tag' ,
        component: index ,
        children: [
            {
                path: 'index' ,
                component: () => import('@vue/view/tag/index.vue') ,
            } ,
        ] ,
    } ,
    {
        path: '/category' ,
        component: index ,
        children: [
            {
                path: 'index' ,
                component: () => import('@vue/view/category/index.vue') ,
            } ,
        ] ,
    } ,
    {
        path: '/module' ,
        component: index ,
        children: [
            {
                path: 'index' ,
                component: () => import('@vue/view/module/index.vue') ,
            } ,
        ] ,
    } ,

    {
        path: '/system' ,
        component: index ,
        children: [
            {
                path: 'settings' ,
                component: () => import('@vue/view/settings/index.vue') ,
            } ,
            {
                path: 'disk' ,
                component: () => import('@vue/view/disk/index.vue') ,
            } ,
            {
                path: 'navigation' ,
                component: () => import('@vue/view/nav/index.vue') ,
            } ,
            {
                path: 'position' ,
                component: () => import('@vue/view/position/index.vue') ,
            } ,
            {
                path: 'imageAtPosition' ,
                component: () => import('@vue/view/image_at_position/index.vue') ,
            } ,
        ] ,
    } ,

    //
    // {
    //     path: '/pannel' ,
    //     component: pannel ,
    //     async: false ,
    // } ,
    //
    // {
    //     path: '/admin_permission/index' ,
    //     component: indexForAdminPermission ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/role/index' ,
    //     component: indexForRole ,
    //     async: true ,
    // } ,
    // {
    //     path: '/module/index' ,
    //     component: indexForModule ,
    //     async: true ,
    // } ,
    // {
    //     path: '/tag/index' ,
    //     component: indexForTag ,
    //     async: true ,
    // } ,
    // {
    //     path: '/category/index' ,
    //     component: indexForCategory ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/image_subject/index' ,
    //     component: indexForImageSubject ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/image_project/index' ,
    //     component: indexForImageProject ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/user/index' ,
    //     component: indexForUser ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/admin/index' ,
    //     component: indexForAdmin ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/position/index' ,
    //     component: indexForPosition ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/image_at_position/index' ,
    //     component: indexForImageAtPosition ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/nav/index' ,
    //     component: indexForNav ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/video/index' ,
    //     component: indexForVideo ,
    //     async: true ,
    // } ,
    // {
    //     path: '/video_series/index' ,
    //     component: indexForVideoSeries ,
    //     async: true ,
    // } ,
    // {
    //     path: '/video_project/index' ,
    //     component: indexForVideoSubject ,
    //     async: true ,
    // } ,
    // {
    //     path: '/video_company/index' ,
    //     component: indexForVideoCompany ,
    //     async: true ,
    // } ,
    //
    // {
    //     path: '/disk/index' ,
    //     component: indexForDisk ,
    //     async: true ,
    // } ,

];
