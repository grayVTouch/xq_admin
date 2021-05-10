// import base from '@vue/view/public/base.vue';

import route from '@vue/router/index.js';

import menu from '../menu.vue';
import navigation from '../navigation.vue';

export default {
    name: 'index' ,

    data () {
        return {
            dom: {} ,
            ins: {} ,
            myValue: {
                pending: {} ,
                fixedNav: false ,
                duration: null ,
                // 当前标签 id
                tabId: '' ,
                // 子视图
                views: [] ,
                // 视图之间传递的参数
                params: [] ,
                // 垂直滚动条的宽度
                fixedNavigation: false ,
                // 文档标题
                title: '' ,
                once: true ,
            } ,

            // 已经产生的标签
            tabs: [] ,
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
            this.dom.topNav = G(this.$refs['top-nav']);
            this.dom.btmNav = G(this.$refs['btm-nav']);
            this.dom.toolbar = G(this.$refs.toolbar);
            this.dom.tabs = G(this.$refs.tabs);
            this.dom.functionForUser = G(this.$refs['functions-for-user']);
            this.dom.tabItems = G(this.$refs['tab-items']);
            this.dom.area = G(this.$refs.area);
            this.dom.info = G(this.$refs.info);
            this.dom.view = G(this.$refs.view);
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
                        this.initAfterLoadedData();
                    });
                });
        } ,

        // 获取当前登录用户信息
        initAfterLoadedData () {
            this.initMenu();
            this.initLeftSlidebar();
        } ,

        initValue () {
            this.myValue.menuInnerW = this.dom.menuInner.width('border-box');
            this.myValue.menuMinW = parseInt(this.dom.menu.getStyleVal('min-width'));
            this.myValue.avatarW = this.dom.avatar.width('border-box');
            this.myValue.avatarH = this.dom.avatar.height('border-box');
            this.myValue.topNavH = this.dom.topNav.height('border-box');
            this.myValue.btmNavH = this.dom.btmNav.height('border-box');
            // this.myValue.infoH = this.dom.info.height('border-box');

            this.myValue.title = document.title;
        } ,

        fixBtmNav () {
            let y = this.dom.view.scrollTop();
            this.myValue.fixedNavigation = !(0 <= y && y < this.myValue.btmNavH);
        } ,

        initArea () {
            const maxH = document.documentElement.clientHeight;
            let areaH = maxH - this.myValue.navH;
            this.dom.area.css({
                height: areaH + 'px'
            });
        } ,

        initEvent () {
            this.dom.win.on('resize' , this.initMenusH.bind(this) , true , false);
            this.dom.win.on('resize' , this.initArea.bind(this) , true , false);
            this.dom.view.on('scroll' , this.fixBtmNav.bind(this) , true , false);
        } ,

        initMenu () {
            // 初始化菜单
            this.$refs['my-menu'].init();
            this.$nextTick(() => {
                const currentRoute = this.state().currentRoute;
                this.createTab(currentRoute.id , currentRoute.cn);
            });
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
            // 固定内部导航栏
            this.fixBtmNav();
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
                // time: 150 ,
                time: 0 ,
                ico: TopContext.res.logo ,
                // 保留首个标签
                saveFirst: true ,
                created (tabId) {
                    // const tab = self.findTabByTabId(tabId);
                    // self.createTabItem(this , tab.tabId , tab.param);
                    // 由于标签可复用，所以为了避免参数的副作用，参数用完立即销毁
                    // 如果需要再次使用，则需重新传递，重新传递仅发生在实际需要要
                    // 参数作用的地方，所以这符合功能要求
                    // tab.param = null;
                } ,

                deleted (tabId , tab) {
                    const index = self.findTabIndexByTabId(tabId);
                    self.tabs.splice(index , 1);
                    if (self.myValue.tabId === tabId) {
                        self.setValue('tabId' , '');
                    }
                } ,

                focus (tabId) {
                    self.setValue('tabId' , tabId);
                    const tab = self.findTabByTabId(tabId);
                    self.initPositions(tab.routeId);
                    self.pushByRouteId(tab.routeId);
                    self.$refs['my-menu'].ins.ic.focus(tab.routeId);
                    // self.$refs['my-menu'].ins.ic.spreadSpecified(tab.routeId);
                    // self.$refs['my-menu'].ins.ic.spreadSpecified(tab.routeId);
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
            this.dom.topNav.removeClass('spread');
            // this.ins.ic.icon('switch');

            G.cookie.set('slidebar' , 'horizontal');
            this.$store.dispatch('slidebar' , G.cookie.get('slidebar'));
        } ,

        vertical () {
            // 滑块切换
            this.dom.vertical.highlight('hide' , this.dom.block.children().get() , true);
            this.dom.avatar.addClass('shrink');
            this.dom.menu.addClass('shrink');
            this.dom.content.addClass('spread');
            this.dom.topNav.addClass('spread');
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
        } ,

        // 侧边菜单栏按钮点击事件
        menuFocusEvent (routeId) {
            this.createTab(routeId);
        } ,

        findTabByRouteId (routeId) {
            for (let i = 0; i < this.tabs.length; ++i)
            {
                const cur = this.tabs[i];
                if (cur.routeId === routeId) {
                    return cur;
                }
            }
            return false;
        } ,

        findTabByTabId (tabId) {
            for (let i = 0; i < this.tabs.length; ++i)
            {
                const cur = this.tabs[i];
                if (cur.tabId === tabId) {
                    return cur;
                }
            }
            return false;
        } ,

        findTabIndexByTabId (tabId) {
            for (let i = 0; i < this.tabs.length; ++i)
            {
                const cur = this.tabs[i];
                if (cur.tabId === tabId) {
                    return i;
                }
            }
            return -1;
        } ,

        // 关闭标签页
        closeTabByTabId (tabId) {
            this.ins.tab.closeTab(tabId);
        } ,

        closeTabByRoute (route) {
            const tab = this.findTabByRoute(route);
            this.closeTabByTabId(tab.tabId);
        } ,

        // 新开一个标签页
        createTab (routeId) {
            const route = this.findRouteById(routeId);
            document.title = route.cn + '-' + this.myValue.title;
            if (TopContext.config.reuseTab) {
                // 标签复用
                const tab = this.findTabByRouteId(routeId);
                if (tab !== false) {
                    // 切换标签
                    this.ins.tab.switch(tab.tabId);
                    return ;
                }
            }
            const tabId = this.ins.tab.create({
                text: route.cn ,
                attr: {
                    routeId ,
                } ,
            });
            // 添加标签页（为标签页复用服务）
            this.tabs.push({
                tabId ,
                routeId ,
            });
        } ,

        // 清除失败的任务
        clearFailedJobs () {
            if (this.pending('clearFailedJobs')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this.pending('clearFailedJobs' , true);
            Api.job.flush().then((res) => {
                this.pending('clearFailedJobs' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.message('success' , '操作成功');
            });
        } ,

        // 重试失败的队列
        retryFailedJobs () {
            if (this.pending('clearFailedJobs')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this.pending('clearFailedJobs' , true);
            Api.job.retry().then((res) => {
                this.pending('clearFailedJobs' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.message('success' , '操作成功');
            });
        } ,
    } ,
};
