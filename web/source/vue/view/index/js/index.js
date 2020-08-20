const imageSubjects = {
    limit: 16 ,
    data: [] ,
    type: 'pro' ,
};

const videoSubjects = {
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

            imageSubjects: G.copy(imageSubjects) ,

            videoSubjects: G.copy(videoSubjects) ,

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
                imageSubject: {
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

                videoSubject: {
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
        this.getHomeSlideshow();

        this.newestInImageSubject();
        this.hotInImageSubject((keep , data) => {
            if (!keep) {
                return ;
            }
            this.hotImages = data;
        });

        this.newestInVideoSubject();
        this.hotTagsInImageSubject();
        this.hotTagsInVideoSubject();
    } ,

    methods: {

        // 图片点赞
        praiseImageSubjectByImageSubject (imageSubject) {
            if (this.pending('praiseImageSubjectByImageSubject')) {
                return ;
            }
            const self = this;
            const action = imageSubject.praised ? 0 : 1;
            this.pending('praiseImageSubjectByImageSubject' , true);
            Api.user.praiseHandle({
                relation_type: 'image_subject' ,
                relation_id: imageSubject.id ,
                action ,
            } , (msg , data , code) => {
                this.pending('praiseImageSubjectByImageSubject' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code , () => {
                        this.praiseImageSubjectByImageSubject(imageSubject)
                    });
                    return ;
                }
                this.handleImageSubject(data);
                for (let i = 0; i <  this.imageSubjects.data.length; ++i)
                {
                    const cur = this.imageSubjects.data[i];
                    if (cur.id === data.id) {
                        this.images.splice(i , 1 ,data);
                        break;
                    }
                }
            })
        } ,

        findImageSubjectByImageSubjectId (imageSubjectId , callback) {
            this.pending('findImageSubjectByImageSubjectId' , true);
            Api.image_subject.show(imageSubjectId , (msg , data , code) => {
                this.pending('findImageSubjectByImageSubjectId' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
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

        // 图片专题-最新图片
        newestInImageSubject () {
            this.pending('image_subject' , true);
            this.group.imageSubject.curTag = 'newest';
            Api.image_subject.newest({
                limit: this.imageSubjects.limit ,
                type: this.imageSubjects.type ,
            } , (msg , data , code) => {
                this.pending('image_subject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.newestImages = data;
                this.imageSubjects.data = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('imageSubject');
                });
            });
        } ,

        // 图片-最热门的图片
        hotInImageSubject (callback) {
            this.pending('image_subject' , true);
            Api.image_subject.hot({
                limit: this.imageSubjects.limit ,
                type: this.imageSubjects.type ,
            } , (msg , data , code) => {
                this.pending('image_subject' , false);
                if (code !== TopContext.code.Success) {
                    G.invoke(callback , this , false , data);
                    this.message('error' , msg);
                    return ;
                }
                G.invoke(callback , this , true , data);
            });
        } ,

        getHotImageSubject () {
            this.pending('image_subject' , true);
            this.group.imageSubject.curTag = 'hot';
            this.hotInImageSubject((keep , data) => {
                this.pending('image_subject' , false);
                if (!keep) {
                    return ;
                }
                this.imageSubjects.data = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('imageSubject');
                });
            })
        } ,

        // 图片-按标签分类获取的图片
        getImageByTagId (tagId) {
            this.pending('image_subject' , true);
            this.group.imageSubject.curTag = 'tag_' + tagId;
            Api.image_subject.getByTagId(tagId , {
                limit: this.imageSubjects.limit ,
                type:  this.imageSubjects.type ,
            } , (msg , data , code) => {
                this.pending('image_subject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.imageSubjects.data = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('imageSubject');
                });
            });
        } ,

        // 图片-按标签分类获取的图片
        hotTagsInImageSubject () {
            this.pending('hotTagsInImageSubject' , true);
            Api.image_subject.hotTags({
                type: this.group.imageSubject.tag.type ,
                limit: this.group.imageSubject.tag.limit ,
            } , (msg , data , code) => {
                this.pending('hotTagsInImageSubject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.group.imageSubject.tag.data = data;
            });
        } ,

        // 图片专题-最新图片
        newestInVideoSubject () {
            this.pending('video_subject' , true);
            this.group.videoSubject.curTag = 'newest';
            Api.video_subject.newest({
                limit: this.videoSubjects.limit ,
                type: this.videoSubjects.type ,
            } , (msg , data , code) => {
                this.pending('video_subject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.videoSubjects.data = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('videoSubject');
                });
            });
        } ,

        hotInVideoSubject () {
            this.group.videoSubject.curTag = 'hot';
            this.pending('video_subject' , true);
            Api.video_subject.hot({
                limit: this.videoSubjects.limit ,
            } , (msg , data , code) => {
                this.pending('video_subject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.videoSubjects.data = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('videoSubject');
                });
            });
        } ,

        getVideoSubjectsByTagId (tagId) {
            this.group.videoSubject.curTag = 'tag_' + tagId;
            this.pending('video_subject' , true);
            Api.video_subject.getByTagId(tagId , {
                limit: this.videoSubjects.limit ,
            } , (msg , data , code) => {
                this.pending('video_subject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.videoSubjects.data = data;
                this.$nextTick(() => {
                    this.initContentGroupContainerWidthByGroup('videoSubject');
                });
            });
        } ,

        // 标签-视频专题
        hotTagsInVideoSubject () {
            this.pending('hotTagsInVideoSubject' , true);
            Api.video_subject.hotTags({
                limit: this.group.videoSubject.tag.limit ,
            } , (msg , data , code) => {
                this.pending('hotTagsInVideoSubject' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.group.videoSubject.tag.data = data;
            });
        } ,

        // 首页幻灯片
        getHomeSlideshow () {
            Api.index.homeSlideshow((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.homeSlideshow = data;
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

            // console.log('initPicPlay_Transform ... !');

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
            inner.css({
                width: width + 'px' ,
                transform: 'translateX(0px)'
            });
            this.group[group].action.translateX = 0;
            this.group[group].action.scrollWidth = width;
            this.group[group].action.clientWidth = parseInt(list.width());
            this.group[group].action.maxTranslateX = 0;
            this.group[group].action.minTranslateX = -(Math.ceil(this.group[group].action.scrollWidth / this.group[group].action.clientWidth) - 1) * this.group[group].action.clientWidth;
        } ,

    } ,
}