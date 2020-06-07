export default {
    name: 'index' ,

    data () {
        return {

        };
    } ,

    mounted () {
        this.initDom();
    } ,

    methods: {
        initDom () {
            this.dom.container = G(this.$el);

            this.dom.menu = G(this.$refs.menu);
            this.dom.content = G(this.$refs.content);

            this.dom.infiniteClassification = G(this.$refs['infinite-classification']);

            // this.dom.leftBtm = G(this.$refs['left-btm']);
            // this.dom.multipleTab = G(this.$refs['multiple-tab']);
            this.dom.win = G(window);

            this.dom.horizontal = G(this.$refs.horizontal);
            this.dom.vertical = G(this.$refs.vertical);
            this.dom.avatar = G(this.$refs.avatar);
            this.dom.block = G(this.$refs.block);


        } ,

        initialize () {
            this.initValue();
            // this.initSlidebar();
            // this.initTab();
            this.initMenu();
            this.initStyle();
            this.initLeftSlidebar();
        } ,

        initValue () {
            this.value.menuW = this.dom.menu.width('border-box');
            this.value.avatarW = this.dom.avatar.width('border-box');
            this.value.avatarH = this.dom.avatar.height('border-box');
        } ,

        // 初始化
        initStyle () {
            /**
             * ********************
             * 左边：菜单
             * ********************
             */
            this.initLeftMidH();
            this.dom.win.on('resize' , this.initLeftMidH.bind(this) , true , false);
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

        // 初始化侧边栏
        initSlidebar () {
            var self = this;
            this.ins.slidebar = new Slidebar(this.dom.slidebar.get(0) , {
                // 动画时间
                time: 300 ,
                // 滑块宽度
                width: '450px' ,
                // 滑块遮罩层透明度
                opacity: 0.3 ,
                // 背景颜色
                backgroundColor: '' ,
                // 状态：show | hide
                status: 'hide' ,
                // 侧边栏方向: left , right
                dir: 'right' ,
                // 是否开启拖拽功能
                enableDrag: false ,
                open () {

                } ,
            });
        } ,

        showSlidebar () {
            this.ins.slidebar.show();
        } ,

        initLeftMidH () {
            let leftH = this.dom.left.height('content-box');
            let leftTopH = this.dom.leftTop.height('border-box');
            let leftBtmH = this.dom.leftBtm.height('border-box');
            let leftMidMinH = parseInt(this.dom.leftMid.css('minHeight'));
            let leftMidH = leftH -leftTopH - leftBtmH;
            leftMidH = Math.max(leftMidMinH , leftMidH);
            this.dom.leftMid.css({
                height: leftMidH + 'px'
            });
        } ,

        initTab () {
            let self = this;
            this.ins.tab = new MultipleTab(this.dom.multipleTab.get(0) , {
                // 如果需要关闭动画，那么把时间调整成 0 试试
                time: 150 ,
                ico: icon ,
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
        initMenu () {
            const self = this;
            this.ins.ic = new InfiniteClassification(this.dom.infiniteClassification.get(0) , {
                // 菜单展开动画过渡时间
                time: 200 ,
                // 次要的图标类型，new || number || switch
                icon: 'switch' ,
                id: [40] ,
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
                    // let topRoute = self.topRoute(id);
                    // let route = self.findRouteById(id);
                    // let text = self.genTabName(topRoute , route);
                    // self.open(text , {
                    //     route: route.route
                    // });
                }
            });
        } ,

        horizontal () {
            this.dom.avatar.removeClass('hide');
            // 滑块切换
            this.dom.horizontal.highlight('hide' , this.dom.block.children().get() , true);
            // 菜单展开
            this.dom.left.animate({
                width: this.value.leftW + 'px' ,
            } , () => {
                this.ins.ic.icon('none');
            });
            // 用户展开
            this.dom.user.animate({
                width: this.value.userW + 'px' ,
                height: this.value.userH + 'px'
            } , () => {
                this.initLeftMidH();
            });
            // 内容收缩
            this.dom.right.animate({
                paddingLeft: this.value.leftW + 'px' ,
            });
            // 右侧顶部导航栏收缩
            this.dom.rightTop.animate({
                paddingLeft: this.value.leftW + 'px' ,
            });
            G.s.set('slidebar' , 'horizontal');
        } ,

        vertical () {
            // 滑块切换
            this.dom.vertical.highlight('hide' , this.dom.block.children().get() , true);
            // 菜单展开
            this.dom.left.animate({
                width: this.value.leftMinW + 'px' ,
            } , () => {
                this.ins.ic.icon('text');
            });
            // 用户收缩
            this.dom.user.animate({
                width: '0px' ,
                height: '0px'
            } , () => {
                this.dom.user.addClass('hide');
                this.initLeftMidH();
            });
            // 内容收缩
            this.dom.right.animate({
                paddingLeft: this.value.leftMinW + 'px' ,
            });
            // 右侧顶部导航栏收缩
            this.dom.rightTop.animate({
                paddingLeft: this.value.leftMinW + 'px' ,
            });
            G.s.set('slidebar' , 'vertical');
        } ,
    } ,
};