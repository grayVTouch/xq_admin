export default {
    findRouteById (id) {
        // 数据扁平化-不带数据结构的数据扁平化
        const permissions = this.permissionsWithoutStruct();
        for (let i = 0; i < permissions.length; ++i)
        {
            const cur = permissions[i];
            if (cur.id === id) {
                return cur;
            }
        }
        return null;
    } ,

    findRouteByPath (path) {
        const permissions = this.permissionsWithoutStruct();
        for (let i = 0; i < permissions.length; ++i)
        {
            const cur = permissions[i];
            if (cur.path === path) {
                return cur;
            }
        }
        return null;
    } ,

    permissionsWithoutStruct () {
        return G.tree.flat(this.state().permissions);
    } ,

    initPositions (id , res = []) {
        const permissions = this.permissionsWithoutStruct();
        const positions = G.tree.parents(id , permissions , {
            id: 'id' ,
            p_id: 'parentId' ,
        } , true , false);
        this.$store.dispatch('topRoute' , positions[0]);
        this.$store.dispatch('currentRoute' , positions[positions.length - 1]);
        this.$store.dispatch('positions' , positions);
    } ,

    pushByRouteId (routeId) {
        const route = this.findRouteById(routeId);
        if (G.isNull(route)) {
            throw new Error('未找到id: ' + routeId + '映射的路由');
        }
        if (this.$route.path !== route.path) {
            // 跳转到给定路由
            this.$router.push(route.path);
        }
    } ,
};
