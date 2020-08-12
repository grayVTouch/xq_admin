const NotFoundView = () => import('../view/error/404.vue');
const login = () => import('../view/login/login.vue');
const index = () => import('../view/index/index.vue');
const pannel = () => import('../view/pannel/pannel.vue');
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
        path: '/admin_permission/index' ,
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

    {
        path: '/user/index' ,
        component: indexForUser
    } ,

    {
        path: '/admin/index' ,
        component: indexForAdmin
    } ,

    {
        path: '/position/index' ,
        component: indexForPosition
    } ,

    {
        path: '/image_at_position/index' ,
        component: indexForImageAtPosition
    } ,

    {
        path: '/nav/index' ,
        component: indexForNav
    } ,

    {
        path: '/video/index' ,
        component: indexForVideo
    } ,
    {
        path: '/video_series/index' ,
        component: indexForVideoSeries
    } ,
    {
        path: '/video_subject/index' ,
        component: indexForVideoSubject
    } ,
    {
        path: '/video_company/index' ,
        component: indexForVideoCompany
    } ,

    {
        path: '/disk/index' ,
        component: indexForDisk ,
    } ,

];