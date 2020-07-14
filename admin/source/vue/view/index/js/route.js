export default {
    data () {
        return {
            field: {
                id: 'id' ,
                p_id: 'p_id'
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
            let routes = this.$store.state.permission;
            let route = G.t.current(id , routes , this.field);
            if (G.isNull(route)) {
                throw new Error('未找到当前 id：' + id + ' 对应路由！');
            }
            return route;
        } ,

        // 获取当前路由，by route
        findRouteByRoute (route) {
            let routes = this.$store.state.permission;
            for (let i = 0; i < routes.length; ++i)
            {
                let cur = routes[i];
                if (cur.value == route) {
                    return cur;
                }
            }
            throw new Error('未找到给定路由信息：' + route + '；请检查路由是否存在');
        } ,

        // 获取顶级路由
        topRoute (id) {
            let route = this.$store.state.permission;
            let parents = G.t.parents(id , route , this.field , true , true);
            return parents;
        } ,

        // 获取当前位置
        position (id) {
            const route = this.$store.state.permission;
            return G.t.parents(id , route , this.field , true , false);
        } ,
    }
};