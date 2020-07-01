import NotFoundView from '../view/error/404.vue';
import welcome from '../view/welcome/welcome.vue';
import home from '../view/public/home.vue';
import index from '../view/index/index.vue';
import indexForImageSubject from '../view/image_subject/index.vue';
import showForImageSubject from '../view/image_subject/show.vue';
import indexForSearch from '../view/search/index.vue';



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
                name: 'search' ,
                path: 'search' ,
                component: indexForSearch ,
                props: true ,
            } ,
        ] ,
    } ,
]