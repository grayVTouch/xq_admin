import routes from "./routes.js";

const router = new VueRouter({
    routes ,
});

/**
 * ****************
 * 路由守卫
 * ****************
 */
// router.beforeEach((to , from , next) => {
//     // const module = G.s.get('module');
//     // if (G.isNull(module)) {
//     //     if (to.name !== 'index') {
//     //         // 已经跳转到首页
//     //         next({name: 'index'});
//     //         return ;
//     //     }
//     // }
//     next();
// });

export default router;