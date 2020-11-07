import routes from "./routes.js";

const router = new VueRouter({
    routes ,
});

/**
 * ****************
 * 路由守卫
 * ****************
 */
router.beforeEach((to , from , next) => {
    const token = G.cookie.get('token');
    debugger
    if (!G.isEmptyString(token)) {
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
