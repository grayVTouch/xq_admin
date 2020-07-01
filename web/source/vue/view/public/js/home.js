export default {
    name: "home" ,

    data () {
        return {
            val: {
                fixed: false ,
                navTypeList: false ,
                mime: {
                    key: 'image' ,
                    value: '图片' ,
                } ,
                toTop: false ,
            } ,
            dom: {} ,
            ins: {} ,
            search: {
                /**
                 * image
                 * video
                 * article
                 */
                mime: 'image' ,
            } ,
            mimeRange: {
                image: '图片' ,
                video: '视频' ,
                article: '资讯' ,
                bbs: '论坛' ,
            } ,
            nav: [] ,
            keepalive: true ,
            count: 0 ,
        };
    } ,

    beforeRouteUpdate (to , from , next) {
        this.initPosition(to.path);
        // 找到当前路由所在位置
        const position = this.getNavByPath(to.path);
        if (position.length > 0) {
            this.ins.nav.focusById(position[position.length - 1].link);
        } else {
            // 没有选中项
            this.ins.nav.blur();
        }
        next();
    } ,

    mounted () {
        this.initDom();
        this.initNavData();
        this.initEvent();
        this.initStyle();
        this.initToTop();
    } ,

    computed: {

    } ,

    methods: {

        initPosition (path) {
            const position = this.getPositionByPath(path);
            this.dispatch('position' , position);
        } ,

        getPositionByPath (path) {
            const res = [];
            let current = this.findCurrentByPath(path);
            while (current !== false)
            {
                res.push(current);
                current = this.findCurrentById(current.p_id);
            }
            res.reverse();
            return res;
        } ,
        
        getNavByPath (path) {
            const position = this.getPositionByPath(path);
            const res = [];
            for (let i = 0; i < position.length; ++i)
            {
                let cur = position[i];
                if (cur.is_menu) {
                    res.push(cur);
                }
            }
            return res;
        } , 

        // 找到当前路由所在菜单项
        findCurrentByPath (path , data) {
            data = data ? data : this.nav;
            let res = false;
            for (let i = 0; i < data.length; ++i)
            {
                const cur = data[i];
                let route = G.getUri(cur.link);
                // 搜索1：完整匹配
                if (route === path) {
                    res = cur;
                    break;
                }
                route = route.replace(/\/:\w+(\/?)/g , '/.+?$1');
                route = route.replace('/' , '\/');
                if (new RegExp('^' + route + '$').test(path)) {
                    res = cur;
                    break;
                }
                // 循环匹配
                res = this.findCurrentByPath(path , cur.children);
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

        initDom () {
            this.dom.win = G(window);
            this.dom.body = G(document.body);
            this.dom.root = G(this.$el);
            this.dom.navTypeList = G(this.$refs['nav-type-list']);
            this.dom.navMenu = G(this.$refs['nav-menu']);
            this.dom.body = G(this.$refs.body);
            this.dom.toTop = G(this.$refs['to-top']);
        } ,

        initIns () {
            const self = this;
            const position = this.getNavByPath(this.$route.path);
            this.ins.nav = new Nav(this.dom.navMenu.get(0) , {
                click (id) {
                    self.push(id);
                } ,
                // 是否选中项
                focus: true ,
                // 是否选中顶级项
                topFocus: true ,
                // 初始选中的项
                ids: position.length > 0 ? [position[position.length - 1].link] : [] ,
            });
        } ,

        initStyle () {
            // this.dom.body.startTransition('show');
        } ,

        switchSearchType (key , value) {
            this.search.mime = key;
            this._val('mime' , {
                key ,
                value ,
            });
            this.hideNavTypeList();
        } ,

        initNavData () {
            Api.home.nav((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                const nav = G.tree.childrens(0 , data , null , false , true);
                this.nav = nav;
                // 初始化获取获取当前路由所在具体位置
                this.initPosition(this.$route.path);
                this.$nextTick(() => {
                    this.initIns();
                });
            });
        } ,

        mimeTypeEvent () {
            if (this.val.navTypeList) {
                this.hideNavTypeList();
            } else {
                this.showNavTypeList();
            }
        } ,

        showNavTypeList () {
            this._val('navTypeList' , true);
            this.dom.navTypeList.removeClass('hide');
            // 显示
            this.dom.navTypeList.startTransition('show');
        } ,

        hideNavTypeList () {
            this._val('navTypeList' , false);
            this.dom.navTypeList.endTransition('show' , () => {
                this.dom.navTypeList.addClass('hide');
            });
        } ,

        // 标题栏置顶
        fixedHeader () {
            const scrollTop = window.pageYOffset;
            this.val.fixed = scrollTop >= 60;
        } ,

        initToTop () {
            const y = window.pageYOffset;
            if (y === 0) {
                this.dom.toTop.endTransition('show' , () => {
                    this.dom.toTop.removeClass('hide');
                });
            } else {
                this.dom.toTop.removeClass('hide');
                this.dom.toTop.startTransition('show');
            }
        } ,

        toTopEvent () {
            G.scrollTo(300 , 'y' , 0 , 0);
        } ,

        initEvent () {
            this.dom.win.on('click' , this.hideNavTypeList.bind(this));
            // this.dom.root.on('scroll' , this.fixedHeader.bind(this));
            this.dom.win.on('scroll' , this.fixedHeader.bind(this));
            this.dom.toTop.on('click' , this.toTopEvent.bind(this));
            this.dom.win.on('scroll' , this.initToTop.bind(this));
        } ,
    } ,
}