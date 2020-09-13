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
    // 切换路由的时候滚动条重置到 0 的位置
    G.scrollTo(0 , 'y' , 0 , 0);
    // 进入路由对应组件
    next();
});

export default router;