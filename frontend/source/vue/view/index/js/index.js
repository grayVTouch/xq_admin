import base from '../../public/base.vue';
import menu from '../../public/menu.vue';
import route from './route.js';
import routes from '../../../router/routes.js';

export default {
    name: 'index' ,

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                fixedNav: false ,
            } ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initValue();
        this.initTab();
        this.initStyle();
        this.initEvent();
        this.initData();
    } ,

    components: {
        'my-menu': menu ,
    } ,

    mixins: [
        route ,
    ] ,

    methods: {

        initDom () {
            this.dom.tabItemstainer = G(this.$el);
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
            Api.user.info((data , code) => {
                if (code !== TopContext.successCode) {
                    Prompt.alert(data);
                    return ;
                }
                // 数据预处理
                const user = data.user ? data.user : {
                    permission: [] ,
                };
                // 无结构权限列表
                const permission = data.user.permission ? data.user.permission : [];
                // 有结构权限列表
                const permissionWithStructure = G.t.childrens(0 , permission , this.field , false , true);
                this.$store.dispatch('user' , user);
                this.$store.dispatch('permission' , permission);
                this.$store.dispatch('permissionWithStructure' , permissionWithStructure);
                this.$nextTick(() => {
                    this.initAfterLoaded();
                });
            });
        } ,

        // 获取当前登录用户信息
        initAfterLoaded () {
            this.initMenus();
        } ,

        initValue () {
            this.val.menuInnerW = this.dom.menuInner.width('border-box');
            this.val.menuMinW = parseInt(this.dom.menu.getStyleVal('min-width'));
            this.val.avatarW = this.dom.avatar.width('border-box');
            this.val.avatarH = this.dom.avatar.height('border-box');
            this.val.navH = this.dom.nav.height('border-box');
            // this.val.infoH = this.dom.info.height('border-box');

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

        initMenus () {
            const self = this;
            this.ins.ic = new InfiniteClassification(this.dom.menu.get(0) , {
                // 菜单展开动画过渡时间
                time: 200 ,
                // 次要的图标类型，new || number || switch
                icon: 'switch' ,
                // id: [1] ,
                id: [4] ,
                // 初始状态，spread || shrink
                status: 'shrink' ,
                // 层级视觉显示效果
                amount: 12 ,
                // 同层级是否互斥
                exclution: false ,
                // 是否菜单也可被选中
                menuFocus: true ,
                // 点击项后是否选中
                focus: false ,
                // 是否选中顶级菜单
                topFocus: false ,
                // 子级项点击后回调
                child (id) {
                    const topRoute = self.topRoute(id);
                    let route = self.findRouteById(id);
                    let text = self.genTabName(topRoute , route);
                    self.open(text , {
                        route: route.value
                    });
                }
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
            this.initLeftSlidebar();
        } ,

        initLeftSlidebar () {
            let slidebar = G.s.get('slidebar');
            if (G.isNull(slidebar)) {
                return ;
            }
            if (slidebar === 'horizontal') {
                return ;
            }
            this.vertical();
        } ,

        initMenusH () {
            let menuH = this.dom.menu.height('content-box');
            let avatarH = this.dom.avatar.height('border-box');
            let logoH = this.dom.logo.height('border-box');
            let descH = this.dom.desc.height('border-box');
            let menusMinH = parseInt(this.dom.menus.css('minHeight'));
            let menusH = menuH -logoH - avatarH - descH;
                menusH = Math.max(menusMinH , menusH);
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
                created (id) {
                    // 路由参数
                    let param = this.attr(id , 'param');
                        param = G.isValid(param) ? G.jsonDecode(param) : {};
                    self.create(this , id , param);
                } ,
                deleted (id) {
                    self.delete(id);
                } ,
                click (id) {
                    self.switch(id);
                }
            });
        } ,

        horizontal () {
            this.dom.avatar.removeClass('hide');
            // 滑块切换
            this.dom.horizontal.highlight('hide' , this.dom.block.children().get() , true);
            // 菜单展开
            this.dom.menu.animate({
                width: this.val.menuInnerW + 'px' ,
            } , () => {
                this.ins.ic.icon('none');
            });
            // 用户展开
            this.dom.avatar.animate({
                width: this.val.avatarW + 'px' ,
                height: this.val.avatarH + 'px'
            } , () => {
                this.initMenusH();
            });
            // 内容收缩
            this.dom.content.animate({
                paddingLeft: this.val.menuInnerW + 'px' ,
            });
            G.s.set('slidebar' , 'horizontal');
        } ,

        vertical () {
            // 滑块切换
            this.dom.vertical.highlight('hide' , this.dom.block.children().get() , true);
            // 菜单展开
            this.dom.menu.animate({
                width: this.val.menuMinW + 'px' ,
            } , () => {
                this.ins.ic.icon('text');
            });
            // 用户收缩
            this.dom.avatar.animate({
                width: '0px' ,
                height: '0px'
            } , () => {
                this.dom.avatar.addClass('hide');
                this.initMenusH();
            });
            // 内容收缩
            this.dom.content.animate({
                paddingLeft: this.val.menuMinW + 'px' ,
            });
            G.s.set('slidebar' , 'vertical');
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
            G.s.del('token');
            this.push({name: 'login'});
        } ,

        // 新开标签页
        open (text , attr , ico = null) {
            this.ins.tab.create({
                ico ,
                text ,
                attr
            });
        } ,

        // 创建内容
        create (tab , id , param) {
            var route = tab.attr(id , 'route');
            var div = document.createElement('div');
            div = G(div);
            div.data('id' , id);
            div.css({
                height: '100%' ,
            });
            var render = document.createElement('div');
            div.append(render);
            this.dom.tabItems.append(div.get(0));
            this.mount(render , id , route , param);
            div.highlight('hide' , div.parent().children().get() , true);
        } ,

        // 删除内容
        delete (id) {
            this.dom.tabItems.remove(this.item(id));
        } ,

        // 查找给定的项
        item (id) {
            let items = this.dom.tabItems.children();
            for (let i = 0; i < items.length; ++i)
            {
                let cur = items.jump(i , true);
                if (cur.data('id') == id) {
                    // 删除节点
                    return cur.get(0);
                }
            }
            throw new Error('未找到给定节点');
        },

        // 标签切换
        switch (id) {
            G(this.item(id)).highlight('hide' , this.dom.tabItems.children().get() , true);
        } ,

        // 组件
        component (route) {
            for (let i = 0; i < routes.length; ++i)
            {
                let v = routes[i];
                if (v.path == route) {
                    return v.component;
                }
            }
            throw new Error('未找到 route：' + route + '对应的路由');
        } ,

        // 挂载组建
        mount (container , id , route , param) {
            let component = this.component(route);

            // 组件重新挂载的时候，滚动条切换到顶部
            // G.scrollTo(0 , 'y' , 0 , 0);

            // 这个仅适用于 动态导入的组件
            // component().then((module) => {
            //     // 注意 module.default ！
            //     // 具体原因请查看 import 语法解释
            //     // 我知道为什么是 default 了！！
            //     // 请查看组件的具体导出 js
            //     // 你会看到 export default {} 这样的字样
            //     // 所以，这边使用 default 来获取组件
            //     let component = this.newComponent(module.default , route , param , id);
            //     new component().$mount(container);
            // });
            component = this.newComponent(component , route , param , id);
            component = new component();
            component.$mount(container);
        } ,

        // 重新渲染
        reRender (id , route , param) {
            let curRoute = this.findRouteByRoute(route);
            let topRoute = this.topRoute(curRoute.id);
            let title = this.genTabName(topRoute , curRoute);
            this.ins.tab.title(id , title);
            // 更新标签内容
            // 重新渲染元素内容
            let container = this.item(id);
                container = G(container);
            this.mount(container.children().jump(0).get(0) , id , route , param);
        } ,

        // 新开一个标签页
        createTab (route , param = {}) {
            let curRoute = this.findRouteByRoute(route);
            let topRoute = this.topRoute(curRoute.id);
            let title = this.genTabName(topRoute , curRoute);
            // console.log(curRoute , topRoute);
            this.open(title , {
                route: curRoute.route ,
                param: G.jsonEncode(param)
            });
        } ,

        // 实例化 vue 组件
        newComponent (component , route , param , id) {
            let self = this;
            route = this.findRouteByRoute(route);
            let topRoute = this.topRoute(route.id);
            let position = self.position(route.id);
            let mixins = {
                store: this.$store ,
                data () {
                    return {
                        // 当前组件的标识符
                        // 子组件不允许设置这些值！
                        id ,
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
                            return self.reRender(id , route , param);
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
    } ,
};