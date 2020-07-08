export default {
    name: "index" ,
    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                limit: 16 ,
                pending: {} ,
            } ,

            homeSlideshow: [] ,

            background: {
                duration: 5 * 1000 ,
                image: '' ,
                index: 0 ,
            } ,

            // 最热门图片
            hotImages: [] ,
            // 最新图片
            newestImages: [] ,

            // 图片专区
            images: [] ,

            group: {
                image: {
                    action: {
                        scrollWidth: 0 ,
                        clientWidth: 0 ,
                        translateX: 0 ,
                        maxTranslateX: 0 ,
                        minTranslateX: 0 ,
                    } ,
                    curTag: 'newest' ,
                    tag: [] ,
                } ,
            } ,

            //
            initOnce: true ,


        };
    } ,

    // beforeRouteLeave () {
    //     console.log('销毁路由');
    //     // if (this.ins.picPlayTransform instanceof PicPlay_Transform) {
    //     //     this.ins.picPlayTransform.clearTimer();
    //     // }
    // } ,

    mounted () {
        this.initDom();
        this.getHomeSlideshow();
        this.newestInImageSubject();
        this.hotInImageSubject((keep , data) => {
            if (!keep) {
                return ;
            }
            this.hotImages = data;
        });
        this.hotTags();
    } ,

    methods: {

        // 图片点赞
        praiseImageSubjectById (imageSubject) {
            if (this.pending('praiseImageSubjectById')) {
                return ;
            }
            const self = this;
            const action = imageSubject.praised ? 0 : 1;
            this.pending('praiseImageSubjectById' , true);
            Api.user.praiseHandle({
                relation_type: 'image_subject' ,
                relation_id: imageSubject.id ,
                action ,
            } , (data , code) => {
                this.pending('praiseImageSubjectById' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandle(data , code , () => {
                        this.$parent.showUserForm('login');
                    });
                    return ;
                }
                this.handleImageSubject(data);
                for (let i = 0; i <  this.images.length; ++i)
                {
                    const cur = this.images[i];
                    if (cur.id === data.id) {
                        this.images.splice(i , 1 ,data);
                        break;
                    }
                }
            })
        } ,

        findImageSubjectByImageSubjectId (imageSubjectId , callback) {
            this.pending('findImageSubjectByImageSubjectId' , true);
            Api.image_subject.show(imageSubjectId , (data , code) => {
                this.pending('findImageSubjectByImageSubjectId' , false);
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    G.invoke(callback , null , false);
                    return ;
                }
                data.user = data.user ? data.user : {};
                data.subject = data.subject ? data.subject : {};
                data.images = data.images ? data.images : [];
                data.tags = data.tags ? data.tags : [];
                data.module = data.module ? data.module : [];

                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,

        // 图片-最新图片
        newestInImageSubject () {
            this.pending('images' , true);
            this.group.image.curTag = 'newest';
            Api.index.newestInImageSubject({
                limit: this.val.limit
            } , (data , code) => {
                this.pending('images' , false);
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                this.newestImages = data;
                this.images = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('image');
                });
            });
        } ,

        // 图片-最热门的图片
        hotInImageSubject (callback) {
            Api.index.hotInImageSubject({
                limit: this.val.limit
            } , (data , code) => {
                if (code !== TopContext.code.Success) {
                    G.invoke(callback , this , false , data);
                    this.message(data);
                    return ;
                }
                G.invoke(callback , this , true , data);
            });
        } ,

        getHotImageSubject () {
            this.pending('images' , true);
            this.group.image.curTag = 'hot';
            this.hotInImageSubject((keep , data) => {
                this.pending('images' , false);
                if (!keep) {
                    return ;
                }
                this.images = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('image');
                });
            })
        } ,

        // 图片-按标签分类获取的图片
        getImageByTagId (tagId) {
            this.pending('images' , true);
            this.group.image.curTag = 'tag_' + tagId;
            Api.index.getImageByTagId(tagId , {
                limit: this.val.limit
            } , (data , code) => {
                this.pending('images' , false);
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                this.images = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('image');
                });
            });
        } ,

        // 图片-按标签分类获取的图片
        hotTags () {
            Api.index.hotTags({
                limit: 5 ,
            } , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                this.group.image.tag = data;
            });
        } ,

        // 首页幻灯片
        getHomeSlideshow () {
            Api.index.homeSlideshow((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                this.homeSlideshow = data;
                this.$nextTick(() => {
                    this.initPicPlay_Transform();
                    // this.initBackground();
                });
            });
        } ,

        initBackground () {
            const playBackground = () => {
                this.background.image = this.homeSlideshow[this.background.index].__path__;
                this.background.index++;
                if (this.background.index >= this.homeSlideshow.length) {
                    this.background.index = 0;
                }
                // 定时播放
                G.timer.time(playBackground.bind(this) , this.background.duration);
            };
            playBackground();
        } ,

        initDom () {
            this.dom.slidebar = G(this.$refs.slidebar);
        } ,

        // 首页幻灯片
        initPicPlay_Transform () {

            console.log('initPicPlay_Transform ... !');

            this.ins.slidebar = new PicPlay_Transform(this.dom.slidebar.get(0) , {
                // 动画过度时间
                time: 400,
                // 定时器时间
                duration: this.background.duration ,
            })
        } ,

        prevByGroup (group) {
            if (this.group.image.action.translateX >= this.group.image.action.maxTranslateX) {
                return ;
            }
            this.group.image.action.translateX += this.group.image.action.clientWidth;
            const inner = G(this.$refs['inner-for-' + group]);
            inner.css({
                transform: 'translateX(' + this.group.image.action.translateX + 'px)'
            });
        } ,

        nextByGroup (group) {
            if (this.group.image.action.translateX <= this.group.image.action.minTranslateX) {
                return ;
            }
            this.group.image.action.translateX -= this.group.image.action.clientWidth;
            const inner = G(this.$refs['inner-for-' + group]);
            inner.css({
                transform: 'translateX(' + this.group.image.action.translateX + 'px)'
            });
        } ,

        // 初始化内容分组的容器宽度
        initContentGroupContainerWidthByGroup (group) {
            const list = G(this.$refs['list-for-' + group]);
            const inner = G(this.$refs['inner-for-' + group]);
            const items = inner.children();
            let width = 0;
            items.each((item) => {
                item = G(item);
                width += item.getTW();
            });
            inner.css({
                width: width + 'px' ,
                transform: 'translateX(0px)'
            });
            this.group.image.action.translateX = 0;
            this.group.image.action.scrollWidth = width;
            this.group.image.action.clientWidth = parseInt(list.width('content-box'));
            this.group.image.action.maxTranslateX = 0;
            this.group.image.action.minTranslateX = -(Math.ceil(this.group.image.action.scrollWidth / this.group.image.action.clientWidth) - 1) * this.group.image.action.clientWidth;
        } ,


    } ,
}