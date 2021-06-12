const imageProjects = {
    limit: 16 ,
    data: [] ,
    type: 'pro' ,
};

const videoProjects = {
    limit: 16 ,
    data: [] ,
    type: 'pro' ,
};

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

            imageProjects: G.copy(imageProjects) ,

            videoProjects: G.copy(videoProjects) ,

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

            group: {
                imageProject: {
                    action: {
                        scrollWidth: 0 ,
                        clientWidth: 0 ,
                        translateX: 0 ,
                        maxTranslateX: 0 ,
                        minTranslateX: 0 ,
                    } ,
                    curTag: 'newest' ,
                    tag: {
                        limit: 5 ,
                        type: 'pro' ,
                        data: [] ,
                    } ,
                } ,

                videoProject: {
                    action: {
                        scrollWidth: 0 ,
                        clientWidth: 0 ,
                        translateX: 0 ,
                        maxTranslateX: 0 ,
                        minTranslateX: 0 ,
                    } ,
                    curTag: 'newest' ,
                    tag: {
                        data: [] ,
                        limit: 5 ,
                    } ,
                } ,
            } ,

        };
    } ,

    // beforeRouteLeave (to , from , next) {
    //     if (this.ins.picPlayTransform instanceof PicPlay_Transform) {
    //         this.ins.picPlayTransform.clearTimer();
    //     }
    //     next();
    // } ,



    mounted () {
        this.initDom();
        // 首页轮播图
        this.getHomeSlideshow();
        // 最新图片专题
        this.newestInImageProject();
        // 热点图片专题
        this.hotInImageProject()
            .then((data) => {
                this.hotImages = data;
            });

        this.newestInVideoProject();
        // 图片标签
        this.hotTagsInImageProject();
        this.hotTagsInVideoProject();
    } ,

    methods: {

        // 图片点赞
        praiseImageProject (row) {
            if (this.pending('praiseImageProject')) {
                return ;
            }
            const self = this;
            const praised = row.is_praised ? 0 : 1;
            this.pending('praiseImageProject' , true);
            Api.user
                .praiseHandle(null , {
                    relation_type: 'image_project' ,
                    relation_id: row.id ,
                    action: praised ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
                            this.praiseImageProject(row)
                        });
                        return ;
                    }
                    row.is_praised = praised;
                    praised ? row.praise_count++ : row.praise_count--;
                })
                .finally(() => {
                    this.pending('praiseImageProject' , false);
                });
        } ,

        findImageProjectByImageProjectId (imageProjectId , callback) {
            this.pending('findImageProjectByImageProjectId' , true);
            Api.imageProject.show(imageProjectId.then((res) => {
                this.pending('findImageProjectByImageProjectId' , false);
                if (res.code !== TopContext.code.Success) {
                    this.message('error' , res.message);
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
            }));
        } ,

        // 图片专题-最新图片
        newestInImageProject () {
            this.pending('imageProject' , true);
            this.group.imageProject.curTag = 'newest';
            Api.imageProject
                .newest({
                    limit: this.imageProjects.limit ,
                    type: this.imageProjects.type ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code);
                        return ;
                    }
                    this.newestImages = res.data;
                    this.imageProjects.data = res.data;
                    this.$nextTick(() => {
                        this.initContentGroupContainerWidthByGroup('imageProject');
                    });
                })
                .finally(() => {
                    this.pending('imageProject' , false);
                });
        } ,

        // 图片-最热门的图片
        hotInImageProject () {
            return new Promise((resolve) => {
                this.pending('imageProject' , true);
                Api.imageProject
                    .hot({
                        limit: this.imageProjects.limit ,
                        type: this.imageProjects.type ,
                    })
                    .then((res) => {
                        if (res.code !== TopContext.code.Success) {
                            this.errorHandleAtHomeChildren(res.message , res.code);
                            return ;
                        }
                        resolve(res.data);
                    })
                    .finally(() => {
                        this.pending('imageProject' , false);
                    });
            });
        } ,

        getHotImageProject () {
            this.group.imageProject.curTag = 'hot';
            this.hotInImageProject()
                .then((data) => {
                    this.imageProjects.data = data;
                    this.$nextTick(() => {
                        this.initContentGroupContainerWidthByGroup('imageProject');
                    });
                });
        } ,

        // 图片-按标签分类获取的图片
        getImageByTagId (tagId) {
            this.pending('imageProject' , true);
            this.group.imageProject.curTag = 'tag_' + tagId;
            Api.imageProject
                .getByTagId(tagId , {
                    limit: this.imageProjects.limit ,
                    type:  this.imageProjects.type ,
                }).then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code);
                        return ;
                    }
                    this.imageProjects.data = res.data;
                    this.$nextTick(() => {
                        this.initContentGroupContainerWidthByGroup('imageProject');
                    });
                })
                .finally(() => {
                    this.pending('imageProject' , false);
                });
        } ,

        // 图片-按标签分类获取的图片
        hotTagsInImageProject () {
            this.pending('hotTagsInImageProject' , true);
            Api.imageProject
                .hotTags({
                    type: this.group.imageProject.tag.type ,
                    limit: this.group.imageProject.tag.limit ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code);
                        return ;
                    }
                    this.group.imageProject.tag.data = res.data;
                })
                .finally(() => {
                    this.pending('hotTagsInImageProject' , false);
                });
        } ,

        // 图片专题-最新图片
        newestInVideoProject () {
            this.pending('videoProject' , true);
            this.group.videoProject.curTag = 'newest';
            Api.videoProject
                .newest({
                    limit: this.videoProjects.limit ,
                    type: this.videoProjects.type ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message);
                        return ;
                    }
                    this.videoProjects.data = res.data;
                    this.$nextTick(() => {
                        this.initContentGroupContainerWidthByGroup('videoProject');
                    });
                })
                .finally(() => {
                    this.pending('videoProject' , false);
                });
        } ,

        hotInVideoProject () {
            this.group.videoProject.curTag = 'hot';
            this.pending('videoProject' , true);
            Api.videoProject
                .hot({
                    limit: this.videoProjects.limit ,
                })
                .then((res) => {
                    this.pending('videoProject' , false);
                    if (res.code !== TopContext.code.Success) {
                        this.message('error' , res.message);
                        return ;
                    }
                    this.videoProjects.data = res.data;
                    this.$nextTick(() => {
                        this.initContentGroupContainerWidthByGroup('videoProject');
                    });
                });
        } ,

        getVideoProjectsByTagId (tagId) {
            this.group.videoProject.curTag = 'tag_' + tagId;
            this.pending('videoProject' , true);
            Api.videoProject
                .getByTagId(tagId , {
                    limit: this.videoProjects.limit ,
                })
                .then((res) => {
                    this.pending('videoProject' , false);
                    if (res.code !== TopContext.code.Success) {
                        this.message('error' , res.message);
                        return ;
                    }
                    this.videoProjects.data = res.data;
                    this.$nextTick(() => {
                        this.initContentGroupContainerWidthByGroup('videoProject');
                    });
                });
        } ,

        // 标签-视频专题
        hotTagsInVideoProject () {
            this.pending('hotTagsInVideoProject' , true);
            Api.videoProject
                .hotTags({
                    limit: this.group.videoProject.tag.limit ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    this.group.videoProject.tag.data = res.data;
                })
                .finally(() => {
                    this.pending('hotTagsInVideoProject' , false);
                });
        } ,

        // 首页幻灯片
        getHomeSlideshow () {
            Api.slideshow
                .home()
                .then((res) => {
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(res.message , res.code);
                    return ;
                }
                this.homeSlideshow = res.data;
                this.$nextTick(() => {
                    this.initPicPlay_Transform();
                    this.initBackground();
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
            this.ins.slidebar = new PicPlay_Transform(this.dom.slidebar.get(0) , {
                // 动画过度时间
                time: 400,
                // 定时器时间
                duration: this.background.duration ,
                timer: true ,
            })
        } ,

        prevByGroup (group) {
            if (this.group[group].action.translateX >= this.group[group].action.maxTranslateX) {
                return ;
            }
            this.group[group].action.translateX += this.group[group].action.clientWidth;
            const inner = G(this.$refs['inner-for-' + group]);
            inner.css({
                transform: 'translateX(' + this.group[group].action.translateX + 'px)'
            });
        } ,

        nextByGroup (group) {
            if (this.group[group].action.translateX <= this.group[group].action.minTranslateX) {
                return ;
            }
            this.group[group].action.translateX -= this.group[group].action.clientWidth;
            const inner = G(this.$refs['inner-for-' + group]);
            inner.css({
                transform: 'translateX(' + this.group[group].action.translateX + 'px)'
            });
        } ,

        /**
         * 初始化内容分组的容器宽度
         *
         * @param group image-subject | video-subject
         */
        initContentGroupContainerWidthByGroup (group) {
            const list  = G(this.$refs['list-for-' + group]);
            const inner = G(this.$refs['inner-for-' + group]);
            const items = inner.children({
                className: 'item' ,
                tagName: 'div' ,
            });
            const listW = list.width();
            let width = 0;
            items.each((item) => {
                item = G(item);
                width += item.getTW();
            });
            width = width < listW ? listW : width;
            inner.css({
                width: width + 'px' ,
                transform: 'translateX(0px)'
            });
            this.group[group].action.translateX = 0;
            this.group[group].action.scrollWidth = width;
            this.group[group].action.clientWidth = parseInt(list.width('content-box'));
            this.group[group].action.maxTranslateX = 0;
            this.group[group].action.minTranslateX = -(Math.ceil(this.group[group].action.scrollWidth / this.group[group].action.clientWidth) - 1) * this.group[group].action.clientWidth;
        } ,

    } ,
}
