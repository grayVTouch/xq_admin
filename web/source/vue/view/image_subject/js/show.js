export default {
    name: "show" ,
    props: ['id'] ,
    data () {
        return {
            data: {
                user: {} ,
                module: {} ,
                subject: {} ,
                images: [] ,
                tags: [] ,
            } ,
            images: {
                data: [] ,
                limit: 5 ,
            } ,

            dom: {} ,
            ins: {} ,
            val: {
                fixed: false ,
            } ,
            // 收藏夹列表
            favorites: [] ,

            // 收藏夹表单
            collectionGroup: {
                relation_type: 'image_subject' ,
                relation_id: this.id ,
                name: '' ,
            } ,

            // 推荐数据
            recommend: {
                limit: 5 ,
                data: [] ,
            } ,

            newest: {
                limit: 5 ,
                data: [] ,
            } ,
        };
    } ,

    created () {

    } ,

    mounted () {
        this.initDom();
        this.initEvent();
        this.getData();
        this.incrementViewCount();
        this.record();
        this.getNewestData();
        this.getRecommendData();
    } ,

    methods: {

        // 图片点赞
        praiseHandle () {
            if (this.pending('praiseHandle')) {
                return ;
            }
            const self = this;
            const action = this.data.praised ? 0 : 1;
            this.pending('praiseHandle' , true);
            Api.user.praiseHandle({
                relation_type: 'image_subject' ,
                relation_id: this.data.id ,
                action ,
            } , (msg , data , code) => {
                this.pending('praiseHandle' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code , () => {
                        this.praiseHandle();
                    });
                    return ;
                }
                this.data = {...data};
            })
        } ,

        showFavorites () {
            this.dom.myFavorites.removeClass('hide');
            this.dom.myFavorites.startTransition('show');
            this.getFavorites();
        } ,

        hideFavorites () {
            this.dom.myFavorites.endTransition('show' , () => {
                this.dom.myFavorites.addClass('hide');
            });
        } ,

        // 获取我的收藏夹
        getFavorites () {
            this.pending('getFavorites' , true);
            Api.user.collectionGroupWithJudge({
                relation_type: 'image_subject' ,
                relation_id: this.id ,
            } , (msg , data , code) => {
                this.pending('getFavorites' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code , () => {
                        this.getFavorites();
                    });
                    return ;
                }
                this.favorites = data;
            });
        } ,

        // 创建并添加收藏夹
        createAndJoinCollectionGroup () {
            if (this.pending('createAndJoinCollectionGroup')) {
                return ;
            }
            this.pending('createAndJoinCollectionGroup' , true);
            Api.user.createAndJoinCollectionGroup(this.collectionGroup , (msg , data , code) => {
                this.pending('createAndJoinCollectionGroup' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data, code , () => {
                        this.createAndJoinCollectionGroup();
                    });
                    return ;
                }
                // 刷新列表
                this.getFavorites();
                this.getData();
            });
        } ,

        collectionHandle (collectionGroup) {
            const pending = 'collectionHandle_' + collectionGroup.id;
            if (this.pending(pending)) {
                return ;
            }
            this.pending(pending , true);
            const action = collectionGroup.inside ? 0 : 1;
            Api.user.collectionHandle({
                relation_type: 'image_subject' ,
                relation_id: this.id ,
                action ,
                collection_group_id: collectionGroup.id ,
            } , (msg , data , code) => {
                this.pending(pending , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code , () => {
                        this.collectionHandle(collectionGroup);
                    });
                    return ;
                }
                for (let i = 0; i < this.favorites.length; ++i)
                {
                    const cur = this.favorites[i];
                    if (cur.id === data.id) {
                        this.favorites.splice(i , 1 , data);
                        break;
                    }
                }
                this.getData();
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.image_subject.show(this.id , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.pending('getData' , false);
                    this.message(msg);
                    return ;
                }
                this.handleImageSubject(data);
                this.data = {...data};
                // 加载首页图片
                this.images.data = data.images.slice(0 , this.images.limit);
                this.$nextTick(() => {
                    this.pending('getData' , false);
                    this.initPicPreview();
                });
            });
        } ,

        incrementViewCount () {
            Api.image_subject.incrementViewCount(this.id);
        } ,

        record () {
            Api.user.record({
                relation_type: 'image_subject' ,
                relation_id: this.id ,
            });
        } ,

        initPicPreview () {
            var images = [];
            this.data.images.forEach((v) => {
                images.push(v.__path__);
            });
            this.ins.picPreviewAsync = new PicPreview_Async(this.dom.picPreviewAsyncContainer.get(0) , {
                index: 1 ,
                images ,
            });
        },

        imageClick (index) {
            this.ins.picPreviewAsync.show(parseInt(index));
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.html = G(document.documentElement);
            this.dom.images = G(this.$refs.images);
            this.dom.picPreviewAsyncContainer = G(this.$refs['pic-preview-async-container']);
            this.dom.myFavorites = G(this.$refs['my-favorites']);
            this.dom.misc = G(this.$refs['misc']);
            this.dom.newest = G(this.$refs.newest);
        },

        // 图片分段加载
        scrollLoadImageEvent (e) {
            // const y = window.pageYOffset;
            const scrollTop = this.dom.images.getWindowOffsetVal('top');
            const imagesH = this.dom.images.height();
            const clientH = this.dom.html.clientHeight();
            const extraH = clientH - TopContext.val.footerH;

            if (Math.abs(scrollTop) + extraH < imagesH) {
                return ;
            }
            const imagesDataLen = this.images.data.length;
            const dataImagesLen = this.data.images.length;
            if (imagesDataLen >= dataImagesLen) {
                return ;
            }
            this.images.data = this.images.data.concat(this.data.images.slice(imagesDataLen , imagesDataLen + this.images.limit));
        } ,

        scrollWithMiscEvent () {
            const scrollTop = this.dom.newest.getWindowOffsetVal('top');
            // const scrollTop = this.dom.misc.getWindowOffsetVal('top');
            if (scrollTop >= TopContext.val.fixedTop) {
                this._val('fixed' , false);
            } else {
                this._val('fixed' , true);
            }
        } ,

        initEvent () {
            this.dom.win.on('scroll' , this.scrollLoadImageEvent.bind(this));
            this.dom.win.on('scroll' , this.scrollWithMiscEvent.bind(this));
        } ,

        // 获取推荐数据
        getNewestData (){
            this.pending('getNewestData' , true);
            Api.image_subject.newest({
                limit: this.newest.limit
            } ,  (msg , data , code) => {
                this.pending('getNewestData' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.newest.data = data;
            });
        } ,

        // 获取推荐数据
        getRecommendData (){
            this.pending('getRecommendData' , true);
            Api.image_subject.recommend(this.id , {
                limit: this.recommend.limit
            } ,  (msg , data , code) => {
                this.pending('getRecommendData' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.recommend.data = data;
            });
        } ,

        linkToImageSubject (imageSubject) {
            const link = this.genUrl(`/image_subject/${imageSubject.id}/show`);
            this.link(link , '_self');
            window.history.go(0);
        } ,

        focusHandle () {
            if (this.pending('focusHandle')) {
                return ;
            }
            this.pending('focusHandle' , true);
            Api.user.focusHandle({
                user_id: this.data.user_id ,
                action: this.data.user.focused ? 0 : 1 ,
            } , (msg , data , code) => {
                this.pending('focusHandle' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code , () => {
                        this.focusHandle();
                    });
                    return ;
                }
                this.getData();
            });

        } ,
    } ,
}