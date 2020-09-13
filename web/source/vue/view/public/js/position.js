export default {
    methods: {
        initPosition (path) {
            const position = this.getPositionByRoute(path);
            this.dispatch('position' , position);
        } ,

        getPositionByRoute (path) {
            const res = [];
            let current = this.findCurrentByRoute(path);
            while (current !== false)
            {
                res.push(current);
                current = this.findCurrentById(current.parentId);
            }
            res.reverse();
            return res;
        } ,

        getNavByRoute (path) {
            const position = this.getPositionByRoute(path);
            const res = [];
            for (let i = 0; i < position.length; ++i)
            {
                let cur = position[i];
                if (!cur.hidden) {
                    res.push(cur);
                }
            }
            return res;
        } ,

        // 找到当前路由所在菜单项
        findCurrentByRoute (path , data) {
            data = data ? data : this.nav;
            let res = false;
            for (let i = 0; i < data.length; ++i)
            {
                const cur = data[i];
                let route = cur.route;
                // 搜索1：完整匹配
                if (route === path) {
                    res = cur;
                    break;
                }
                // 搜搜2：正则匹配
                route = route.replace(/\/:\w+(\/?)/g , '/.+?$1');
                route = route.replace(/(\/|\?)/g , '\$1');
                if (new RegExp('^' + route + '$').test(path)) {
                    res = cur;
                    break;
                }
                // 嵌套循环匹配
                res = this.findCurrentByRoute(path , cur.children);
                if (res !== false) {
                    break;
                }
            }
            return res;
        } ,

        findCurrentById (id , data) {
            data = data ? data : this.nav;
            let res = false;
            for (let i = 0; i < data.length; ++i)
            {
                const cur = data[i];
                if (cur.id === id) {
                    res = cur;
                    break;
                }
                res = this.findCurrentById(id , cur.children);
                if (res !== false) {
                    break;
                }
            }
            return res;
        } ,

        // 获取当前路由中的最大id
        getMaxIdAtPosition () {
            const ids = [];
            G.tree.loop(this.nav , (v) => {
                ids.push(v.id);
            });
            return Math.max(...ids);
        } ,
    } ,
};