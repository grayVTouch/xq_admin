// import base from '@vue/view/public/base.vue';
import menu from '@vue/view/public/menu.vue';
import route from '@vue/router/index.js';
import routes from '@vue/router/routes.js';
import navigation from '@vue/view/public/navigation.vue';

export default {
    name: 'index' ,

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                fixedNav: false ,
                // 标签页
                tabs: [] ,
                duration: null ,
                // 当前标签 id
                tabId: '' ,
                // 子视图
                views: [] ,
                // 视图之间传递的参数
                params: [] ,
                // 垂直滚动条的宽度
                yScrollbarWidth: 0 ,
            } ,

            // 客户端位置：带数据结构
            positions: [] ,
            // 客户端位置：不带数据结构
            flatPosition: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initValue();
        // this.initPosition();
        this.initTab();
        this.initStyle();
        this.initEvent();
        this.initData();
    } ,

    components: {
        'my-menu': menu ,
        'my-navigation': navigation ,
    } ,

    mixins: [
        route ,
    ] ,

    methods: {
        /**
         * 处理当前位置
         */
        initPosition () {
            const positions = G.copy(this.$store.state.position , true);
            G.tree.handle(positions);
            G.tree.uHandle(positions , (v) => {
                /**
                 * 这边做权限认证
                 */
            });
            this.positions = positions;
            this.flatPosition = G.tree.flat(positions);
        } ,

        initDom () {
            this.dom.main = G(this.$el);
            this.dom.menu = G(this.$refs.menu);
            this.dom.menuInner = G(this.$refs['menu-inner']);
            this.dom.logo = G(this.$refs.logo);
            this.dom.avatar = G(this.$refs.avatar);
            this.dom.block = G(this.$refs.block);
            this.dom.horizontal = G(this.$refs.horizontal);
            this.dom.vertical = G(this.$refs.vertical);
            this.dom.menus = G(this.$refs.menus);
            this.dom.desc = G(this.$refs.desc);
            this.dom.content = G(this.$refs.content);
            this.dom.win = G(window);
            this.dom.nav = G(this.$refs.nav);
            this.dom.toolbar = G(this.$refs.toolbar);
            this.dom.tabs = G(this.$refs.tabs);
            this.dom.functionForUser = G(this.$refs['functions-for-user']);
            this.dom.tabItems = G(this.$refs['tab-items']);
            this.dom.area = G(this.$refs.area);
            this.dom.info = G(this.$refs.info);
        } ,

        initData () {
            Api.admin.info()
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    const user = res.data ? res.data : {
                        permission: []
                    };
                    // 无结构权限列表
                    const permission = user.permission ? user.permission : [];
                    // 有结构权限列表
                    const permissionWithStructure = G.tree.childrens(0 , permission , this.field , false , true);
                    this.$store.dispatch('user' , user);
                    this.$store.dispatch('myPermission' , permission);
                    this.$store.dispatch('myPermissionWithStructure' , permissionWithStructure);
                    this.$nextTick(() => {
                        this.initAfterLoaded();
                    });
                });
        } ,

        // 获取当前登录用户信息
        initAfterLoaded () {
            this.initMenu();
            this.initLeftSlidebar();
        } ,

        initValue () {
            this.val.menuInnerW = this.dom.menuInner.width('border-box');
            this.val.menuMinW = parseInt(this.dom.menu.getStyleVal('min-width'));
            this.val.avatarW = this.dom.avatar.width('border-box');
            this.val.avatarH = this.dom.avatar.height('border-box');
            this.val.navH = this.dom.nav.height('border-box');
            // this.val.infoH = this.dom.info.height('border-box');
            this.val.yScrollbarWidth = this.dom.main.yScrollbarWidth();
        } ,

        initArea () {
            const maxH = document.documentElement.clientHeight;
            let areaH = maxH - this.val.navH;
            this.dom.area.css({
                height: areaH + 'px'
            });
        } ,

        initEvent () {
            this.dom.win.on('resize' , this.initMenusH.bind(this) , true , false);
            this.dom.win.on('resize' , this.initArea.bind(this) , true , false);
        } ,

        initMenu () {

        } ,

        // 初始化
        initStyle () {
            /**
             * ********************
             * 左边：菜单
             * ********************
             */
            this.initMenusH();
            this.initArea();
        } ,

        initLeftSlidebar () {
            let slidebar = G.cookie.get('slidebar');
            if (G.isEmptyString(slidebar)) {
                return ;
            }
            if (slidebar === 'horizontal') {
                return ;
            }
            this.vertical();
        } ,

        initMenusH () {
            let menuH = this.dom.menu.height('content-box');
            let avatarH = this.dom.avatar.getTH();
            let logoH = this.dom.logo.getTH();
            let blockH = this.dom.block.getTH();
            let descH = this.dom.desc.getTH();
            let menusH = menuH -logoH - avatarH - blockH - descH;
            this.dom.menus.css({
                height: menusH + 'px'
            });
        } ,

        initTab () {
            let self = this;
            this.ins.tab = new MultipleTab(this.dom.tabs.get(0) , {
                // 如果需要关闭动画，那么把时间调整成 0 试试
                time: 150 ,
                ico: TopContext.res.logo ,
                created (tabId) {
                    const tab = self.findTabByTabId(tabId);
                    self.createTabItem(this , tab.tabId , tab.param);
                    // 由于标签可复用，所以为了避免参数的副作用，参数用完立即销毁
                    // 如果需要再次使用，则需重新传递，重新传递仅发生在实际需要要
                    // 参数作用的地方，所以这符合功能要求
                    tab.param = null;
                } ,
                deleted (tabId , tab) {
                    self.deleteTabMappingContent(tabId);
                    if (tabId === self._val('tabId')) {
                        self._val('tabId' , '');
                    }
                } ,
                focus (tabId) {
                    self.switchTabItemByTabId(tabId);
                    self._val('tabId' , tabId);

                } ,
            });
        } ,

        horizontal () {
            // this.dom.avatar.removeClass('hide');
            // 滑块切换
            this.dom.horizontal.highlight('hide' , this.dom.block.children().get() , true);

            this.dom.avatar.removeClass('shrink');
            this.dom.menu.removeClass('shrink');
            this.dom.content.removeClass('spread');
            this.dom.nav.removeClass('spread');
            // this.ins.ic.icon('switch');
            //
            // // 菜单展开
            // this.dom.menu.animate({
            //     width: this.val.menuInnerW + 'px' ,
            // } , () => {
            //     this.ins.ic.icon('none');
            // } , this.val.duration);
            //
            // // 用户展开
            // this.dom.avatar.animate({
            //     width: this.val.avatarW + 'px' ,
            //     height: this.val.avatarH + 'px'
            // } , () => {
            //     this.initMenusH();
            // } , this.val.duration);
            //
            // // 内容收缩
            // this.dom.content.animate({
            //     paddingLeft: this.val.menuInnerW + 'px' ,
            // } , null , this.val.duration);

            G.cookie.set('slidebar' , 'horizontal');
            this.$store.dispatch('slidebar' , G.cookie.get('slidebar'));
        } ,

        vertical () {
            // 滑块切换
            this.dom.vertical.highlight('hide' , this.dom.block.children().get() , true);

            this.dom.avatar.addClass('shrink');
            this.dom.menu.addClass('shrink');
            this.dom.content.addClass('spread');
            this.dom.nav.addClass('spread');
            // this.ins.ic.icon('text');

            // 菜单展开
            // this.dom.menu.animate({
            //     time: 3000 ,
            //     width: this.val.menuMinW + 'px' ,
            // } , () => {
            //     this.ins.ic.icon('text');
            // } , this.val.duration);
            //
            // // 用户收缩
            // this.dom.avatar.animate({
            //     width: '0px' ,
            //     height: '0px'
            // } , () => {
            //     this.dom.avatar.addClass('hide');
            //     this.initMenusH();
            // } , this.val.duration);
            // // 内容收缩
            // this.dom.content.animate({
            //     paddingLeft: this.val.menuMinW + 'px' ,
            // } , null , this.val.duration);
            G.cookie.set('slidebar' , 'vertical');
            this.$store.dispatch('slidebar' , G.cookie.get('slidebar'));
        } ,

        // 显示
        showUserCtrl () {
            this.dom.functionForUser.removeClass('hide');
            this.dom.functionForUser.animate({
                opacity: 1 ,
                bottom: '0px'
            });
        } ,
        // 隐藏
        hideUserCtrl () {
            this.dom.functionForUser.animate({
                opacity: 0 ,
                bottom: '-10px'
            } , () => {
                this.dom.functionForUser.addClass('hide');
            });
        } ,

        // 注销
        logout () {
            G.cookie.del('token');
            window.history.go(0);
            // this.push({name: 'login'});
        } ,

        findTabByRoute (route) {
            for (let i = 0; i < this.val.tabs.length; ++i)
            {
                const cur = this.val.tabs[i];
                if (cur.route === route) {
                    return cur;
                }
            }
            return false;
        } ,

        findTabByTabId (tabId) {
            for (let i = 0; i < this.val.tabs.length; ++i)
            {
                const cur = this.val.tabs[i];
                if (cur.tabId === tabId) {
                    return cur;
                }
            }
            return false;
        } ,

        findTabIndexByTabId (tabId) {
            for (let i = 0; i < this.val.tabs.length; ++i)
            {
                const cur = this.val.tabs[i];
                if (cur.tabId === tabId) {
                    return i;
                }
            }
            return -1;
        } ,

        // 新开标签页
        open (text , route , param = {} , ico = null) {
            if (TopContext.config.reuseTab) {
                const tab = this.findTabByRoute(route);
                if (tab !== false) {
                    // 切换标签
                    this.ins.tab.switchById(tab.tabId);
                    // 切换标签对应内容
                    this.switchTabItemByTabId(tab.tabId);
                    // 重新渲染标签对应内容
                    // this.reRender(tab.tabId , route , param);
                    return ;
                }
            }
            const tabId = this.ins.tab.create({
                ico ,
                text ,
                attr: {
                    route ,
                } ,
            });
            // 添加标签页（为标签页复用服务）
            this.val.tabs.push({
                tabId ,
                route ,
                param ,
            });
        } ,

        // 创建内容
        createTabItem (tab , id , param) {
            var route = tab.attr(id , 'route');
            var div = document.createElement('div');
            div = G(div);
            div.addClass(['item']);
            div.data('id' , id);
            div.css({
                height: '100%' ,
            });
            var render = document.createElement('div');
            div.append(render);
            this.dom.tabItems.append(div.get(0));
            this.mount(render , id , route , param);

            // console.log(div.get(0) , div.parent().children().get());
            // div.highlight('cur' , div.parent().children().get());
            div.highlight('hide' , div.parent().children().get() , true);
        } ,

        // 删除标签对应内容
        deleteTabMappingContent (tabId) {
            // 菜单失去焦点
            const tab = this.findTabByTabId(tabId);
            const route = tab.route;
            const deletedRoute = this.findRouteByRoute(route);
            this.ins.ic.blur(deletedRoute.id);

            // 移除标签对应内容
            const indexInTabs = this.findTabIndexByTabId(tabId);
            this.val.tabs.splice(indexInTabs , 1);

            // 移除标签对应内容
            this.dom.tabItems.remove(this.findTabItemByTabId(tabId));
        } ,

        // 关闭标签页（删除标签 + 标签对应内容）
        closeTabByTabId (tabId) {
            this.ins.tab.closeTab(tabId);
        } ,

        closeTabByRoute (route) {
            const tab = this.findTabByRoute(route);
            this.closeTabByTabId(tab.tabId);
        } ,

        // 查找给定的项
        findTabItemByTabId (tabId) {
            let items = this.dom.tabItems.children();
            for (let i = 0; i < items.length; ++i)
            {
                let cur = items.jump(i , true);
                if (cur.data('id') == tabId) {
                    // 删除节点
                    return cur.get(0);
                }
            }
            throw new Error('未找到给定节点');
        },

        // 标签切换
        switchTabItemByTabId (tabId) {
            // 切换菜单项
            const route = this.ins.tab.attr(tabId , 'route');
            const curRoute = this.findRouteByRoute(route);
            this.ins.ic.spreadSpecified(curRoute.id , false);

            // 切换标签对应内容
            const tabItem = G(this.findTabItemByTabId(tabId));
            // tabItem.highlight('cur' , tabItem.parent().children().get());
            tabItem.highlight('hide' , tabItem.parent().children().get() , true);
        } ,

        // 获取当前路由
        component (route) {
            let emptyPage = null;
            for (let i = 0; i < routes.length; ++i)
            {
                let v = routes[i];
                if (v.path == route) {
                    return v;
                }
                if (v.name === '404') {
                    // 默认错误页面
                    emptyPage = v;
                }
            }
            return emptyPage;
        } ,

        // 挂载组建
        mount (container , tabId , route , param) {
            let component = this.component(route);

            // 组件重新挂载的时候，滚动条切换到顶部
            // G.scrollTo(0 , 'y' , 0 , 0);
            // 这个仅适用于 动态导入的组件
            if (component.async) {
                // 异步路由
                const asyncComponent = component.component();
                asyncComponent.then((module) => {
                    // 注意 module.default ！
                    // 具体原因请查看 import 语法解释
                    // 我知道为什么是 default 了！！
                    // 请查看组件的具体导出 js
                    // 你会看到 export default {} 这样的字样
                    // 所以，这边使用 default 来获取组件
                    let component = this.newComponent(module.default , route , param , tabId);
                    component = new component();
                    component.$mount(container);
                });
            } else {
                // 同步路由
                let syncComponent = this.newComponent(component.component , route , param , tabId);
                syncComponent = new syncComponent();
                syncComponent.$mount(container);
            }
        } ,

        // 重新渲染
        reRender (tabId , route , param) {
            let curRoute = this.findRouteByRoute(route);
            let topRoute = this.topRoute(curRoute.id);
            let title = this.genTabName(topRoute , curRoute);
            // 重新渲染标签名称
            this.ins.tab.title(tabId , title);
            // 重新渲染标签对应内容
            let container = this.findTabItemByTabId(tabId);
                container = G(container);
            this.mount(container.children().jump(0).get(0) , tabId , route , param);
        } ,

        // 新开一个标签页
        createTab (route , param = {}) {
            let curRoute = this.findRouteByRoute(route);
            let topRoute = this.topRoute(curRoute.id);
            let title = this.genTabName(topRoute , curRoute);
            // console.log(curRoute , topRoute);
            this.open(title , route , param);
        } ,

        // 实例化 vue 组件
        newComponent (component , route , param , tabId) {
            let self = this;
            route = this.findRouteByRoute(route);
            let topRoute = this.topRoute(route.id);
            let position = this.position(route.id);
            let mixins = {
                // store: this.$store ,
                data () {
                    return {
                        // 当前组件的标识符
                        // 子组件不允许设置这些值！
                        id: tabId ,
                        param ,
                        route ,
                        topRoute ,
                        position ,
                    };
                } ,
                methods: {
                    // 也跳跳转方法
                    location (route , param , type = '_self') {
                        // 目前仅有两种类型
                        // _self 页面内重载
                        // _blank 打开新的标签页
                        let typeRange = ['_self' , '_blank'];
                        if (type == '_self') {
                            return self.reRender(tabId , route , param);
                        }
                        if (type == '_blank') {
                            // 新开一个标签页
                            return self.createTab(route , param);
                        }
                        // ...预留的内容
                    } ,
                    // 刷新操作
                    reload () {
                        this.location(this.route.value , this.param);
                    } ,

                    // 关闭标签页
                    closeTabByTabId (tabId) {
                        self.closeTabByTabId(tabId);
                    } ,

                    closeTabByRoute (route) {
                        self.closeTabByRoute(route);
                    } ,

                    // 标签页切换

                    // todo 后退
                    prev () {} ,

                    // todo 前进
                    next () {} ,

                } ,
                components: {
                    "my-base": base
                }
            };
            component.mixins = component.mixins ? component.mixins : [];
            // console.log(component.$parent , component.mixins);
            // component.$parent.mixins = component.$parent.mixins ? component.$parent.mixins : [];

            component.mixins.push(mixins);
            // component.$parent.mixins.push(mixins);

            /**
             * 注意！必须是 component 对象的拷贝
             * 负责实例化相同的组件时
             * vue 只会实例化一次
             * 之后便不会在实例化
             * 这导致的后果就是
             * 标签重载的时候看不到有重载的效果
             * 实际上就是服用了之前的组件
             * 压根就没有重载
             */
            return Vue.extend({
                ...component
            });
        } ,

        // 重新加载子页面
        reloadChildPage () {
            const tabId = this._val('tabId');
            if (!G.isValid(tabId)) {
                return ;
            }
            const route = this.ins.tab.attr(tabId , 'route');
            this.reRender(tabId , route);
        } ,

        clearFailedJobs () {
            if (this.pending('clearFailedJobs')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this.pending('clearFailedJobs' , true);
            Api.job.flush((msg , data , code) => {
                this.pending('clearFailedJobs' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.message('success' , '操作成功');
            });
        } ,

        retryFailedJobs () {
            if (this.pending('clearFailedJobs')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this.pending('clearFailedJobs' , true);
            Api.job.retry((msg , data , code) => {
                this.pending('clearFailedJobs' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.message('success' , '操作成功');
            });
        } ,
    } ,
};
