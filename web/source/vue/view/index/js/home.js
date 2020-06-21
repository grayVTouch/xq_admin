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
            category: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        // this.initIns();
        this.initStyle();
        this.getCategoryData();
        this.initEvent();
    } ,

    methods: {
        initDom () {
            this.dom.win = G(window);
            this.dom.root = G(this.$el);
            this.dom.navTypeList = G(this.$refs['nav-type-list']);
            this.dom.navMenu = G(this.$refs['nav-menu']);
            this.dom.body = G(this.$refs.body);
        } ,

        initIns () {

            this.ins.nav = new Nav(this.dom.navMenu.get(0) , {

            });
        } ,

        initStyle () {
            this.dom.body.startTransition('show');
        } ,

        switchSearchType (key , value) {
            this.search.mime = key;
            this._val('mime' , {
                key ,
                value ,
            });
            this.hideNavTypeList();
        } ,

        getCategoryData () {
            Api.category.all((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                const category = G.tree.childrens(0 , data , null , false , true);
                this.category = category;
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
            const scrollTop = this.dom.root.scrollTop();
            this.val.fixed = scrollTop >= 60;
        } ,

        initEvent () {
            this.dom.win.on('click' , this.hideNavTypeList.bind(this));
            this.dom.root.on('scroll' , this.fixedHeader.bind(this));
        } ,
    } ,
}