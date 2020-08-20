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
    const module = G.s.get('module');
    if (G.isNull(module)) {
        if (to.name !== 'welcome') {
            // 以默认模块进入页面
            Api.module.default((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                G.s.json('module' , data);
                next();
            });
            return ;
        }
    }
    next();
});

export default router;