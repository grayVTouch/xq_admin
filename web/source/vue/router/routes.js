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
const indexForChannel = () => import('../view/channel/index.vue');
const imageForChannel = () => import('../view/channel/image.vue');
const myFocusUser = () => import('../view/channel/my_focus_user.vue');
const focusMeUser = () => import('../view/channel/focus_me_user.vue');
const indexForCollectionGroup = () => import('../view/collection_group/index.vue');
const imageForcollectionGroup = () => import('../view/collection_group/image.vue');


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
                path: 'image_subject' ,
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
                path: 'collection_group/:id' ,
                component: indexForCollectionGroup ,
                props: true ,
                children: [
                    {
                        path: 'image' ,
                        component: imageForcollectionGroup
                    }
                ] ,
            } ,
            {
                name: 'channel' ,
                path: 'channel/:id' ,
                redirect: 'channel/:id/image' ,
                component: indexForChannel ,
                props: true ,
                children: [
                    {
                        path: 'image' ,
                        component: imageForChannel ,
                    } ,
                    {
                        path: 'my_focus_user' ,
                        component: myFocusUser
                    } ,
                    {
                        path: 'focus_me_user' ,
                        component: focusMeUser ,
                    } ,
                ] ,
            } ,
            {
                path: 'user' ,
                component: indexForUser ,
                redirect: '/user/info' ,
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