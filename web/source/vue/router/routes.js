import NotFoundView from '../view/error/404.vue';
import index from '../view/index/index.vue';



export default [
    {
        name: '404' ,
        path: '/404' ,
        component: NotFoundView
    } ,
    {
        name: 'index' ,
        path: '/' ,
        component: index ,
    }

]