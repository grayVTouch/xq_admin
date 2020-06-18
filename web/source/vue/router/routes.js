import NotFoundView from '../view/error/404.vue';
import welcome from '../view/index/welcome.vue';
import home from '../view/index/home.vue';
import index from '../view/index/index.vue';



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
            }
        ] ,
    } ,
]