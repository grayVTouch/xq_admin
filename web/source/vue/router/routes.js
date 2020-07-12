// import NotFoundView from '../view/error/404.vue';
// import welcome from '../view/welcome/welcome.vue';
// import home from '../view/public/home.vue';
// import index from '../view/index/index.vue';
// import indexForImageSubject from '../view/image_subject/index.vue';
// import showForImageSubject from '../view/image_subject/show.vue';
// import searchForImageSubject from '../view/image_subject/search.vue';
// import indexForUser from '../view/user/index.vue';
// import info from '../view/user/info.vue';
// import password from '../view/user/password.vue';
// import history from '../view/user/history.vue';
// import favorites from '../view/user/favorites.vue';


const NotFoundView = () => import('../view/error/404.vue');
const welcome = () => import('../view/welcome/welcome.vue');
const home  = () => import('../view/public/home.vue');
const index = () => import('../view/index/index.vue');
const indexForImageSubject = () => import('../view/image_subject/index.vue');
const showForImageSubject = () => import('../view/image_subject/show.vue');
const searchForImageSubject = () => import('../view/image_subject/search.vue');
const indexForUser = () => import('../view/user/index.vue');
const info = () => import('../view/user/info.vue');
const password = () => import('../view/user/password.vue');
const history = () => import('../view/user/history.vue');
const favorites = () => import('../view/user/favorites.vue');


export default [
    {
        name: '404' ,
        path: '*' ,
        component: NotFoundView
    } ,
    {
        name: 'welcome' ,
        path: '/welcome' ,
        component: welcome ,
    } ,
    {
        name: 'home' ,
        path: '/' ,
        component: home ,
        redirect: '/index' ,
        children: [
            {
                name: 'index' ,
                path: 'index' ,
                component: index
            } ,
            {
                path: 'image_subject/index' ,
                component: indexForImageSubject ,
            } ,
            {
                path: 'image_subject/:id/show' ,
                component: showForImageSubject ,
                props: true ,
            } ,
            {
                path: 'image_subject/search' ,
                component: searchForImageSubject ,
                props: true ,
            } ,
            {
                path: 'user' ,
                component: indexForUser ,
                children: [
                    {
                        path: 'info' ,
                        component: info
                    } ,
                    {
                        path: 'password' ,
                        component: password
                    } ,
                    {
                        path: 'history' ,
                        component: history
                    } ,
                    {
                        path: 'favorites' ,
                        component: favorites
                    } ,
                ] ,
            } ,
        ] ,
    } ,
]