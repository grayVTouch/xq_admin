/**
 * @author running
 */

import routes from './routes.js';

const router = new VueRouter({
    routes ,
});

router.beforeEach((to , from , next) => {
    const logined = G.s.get('logined');

    console.log('logined' , logined);

    if (!G.isNull(logined)) {
        if (to.name === 'login') {
            // 已经跳转到首页
            next({name: 'home'});
            return ;
        }
    } else {
        // 未登录
        if (to.name !== 'login') {
            next({name: 'login'});
            return ;
        }
    }
    next();

});

export default router;