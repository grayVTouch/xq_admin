export default {
    data () {
        return {
            field: {
                id: 'id' ,
                p_id: 'parentId'
            } ,
        };
    } ,
    methods: {

        // 生成标签名称
        genTabName (topRoute , curRoute) {
            return `${topRoute.cn}-${curRoute.cn}`;
        } ,

        // 获取当前路由 by id
        findRouteById (id) {
            let routes = this.flatPosition;
            let route = G.t.current(id , routes , this.field);
            if (G.isNull(route)) {
                throw new Error('未找到当前 id：' + id + ' 对应路由！');
            }
            return route;
        } ,

        // 获取当前路由，by route
        findRouteByRoute (route) {
            let routes = this.flatPosition;
            for (let i = 0; i < routes.length; ++i)
            {
                let cur = routes[i];
                if (cur.path == route) {
                    return cur;
                }
            }
            throw new Error('未找到给定路由信息：' + route + '；请检查路由是否存在');
        } ,

        // 获取顶级路由
        topRoute (id) {
            let route = this.flatPosition;
            let parents = G.t.parents(id , route , this.field , true , true);
            return parents;
        } ,

        // 获取当前位置
        position (id) {
            let route = this.flatPosition;
            return G.t.parents(id , route , this.field , true , false);
        } ,
    }
};
