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
                relation_type: 'image_project' ,
                relation_id: this.id ,
                name: '' ,
            } ,

            // 推荐数据
            recommend: {
                limit: 5 ,
                data: [] ,
                type: 'pro' ,
            } ,

            newest: {
                limit: 5 ,
                data: [] ,
                type: 'pro' ,
            } ,
        };
    } ,

    created () {

    } ,

    beforeRouteUpdate (to , from , next) {
        this.reload();
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
            Api.user
                .praiseHandle(null , {
                    relation_type: 'image_project' ,
                    relation_id: this.data.id ,
                    action ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
                            this.praiseHandle();
                        });
                        return ;
                    }
                    this.data = {...data};
                })
                .finally(() => {
                    this.pending('praiseHandle' , false);
                });
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
            Api.user
                .collectionGroupWithJudge({
                    relation_type: 'image_project' ,
                    relation_id: this.id ,
                })
                .then((res) => {

                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
                            this.getFavorites();
                        });
                        return ;
                    }
                    this.favorites = data;
                })
                .finally(() => {
                    this.pending('getFavorites' , false);
                });
        } ,

        // 创建并添加收藏夹
        createAndJoinCollectionGroup () {
            if (this.pending('createAndJoinCollectionGroup')) {
                return ;
            }
            this.pending('createAndJoinCollectionGroup' , true);
            Api.user
                .createAndJoinCollectionGroup(this.collectionGroup)
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
                            this.createAndJoinCollectionGroup();
                        });
                        return ;
                    }
                    // 刷新列表
                    this.getFavorites();
                    this.getData();
                })
                .finally(() => {
                    this.pending('createAndJoinCollectionGroup' , false);
                });
        } ,

        collectionHandle (collectionGroup) {
            const pending = 'collectionHandle_' + collectionGroup.id;
            if (this.pending(pending)) {
                return ;
            }
            this.pending(pending , true);
            const action = collectionGroup.inside ? 0 : 1;
            Api.user
                .collectionHandle({
                    relation_type: 'image_project' ,
                    relation_id: this.id ,
                    action ,
                    collection_group_id: collectionGroup.id ,
                })
                .then((res) => {
                    this.pending(pending , false);
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
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
                })
                .finally(() => {

                });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.imageProject
                .show(this.id)
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code);
                        return ;
                    }
                    const data = res.data;
                    this.handleData(data);
                    this.data = data;
                    // 加载首页图片
                    this.images.data = data.images.slice(0 , this.images.limit);
                    this.$nextTick(() => {
                        this.initPicPreview();
                    });
                })
                .finally(() => {
                    this.pending('getData' , false);
                });
        } ,

        handleData (data) {
            data.user    = data.user ? data.user : {};
            data.image_subject = data.image_subject ? data.image_subject : {};
            data.images  = data.images ? data.images : [];
            data.tags    = data.tags ? data.tags : [];
            data.module  = data.module ? data.module : [];
        } ,

        incrementViewCount () {
            this.pending('incrementViewCount' , true);
            Api.imageProject
                .incrementViewCount(this.id)
                .finally(() => {
                    this.pending('incrementViewCount' , false);
                });
        } ,

        record () {
            this.pending('record' , true);
            Api.user
                .record({
                    relation_type: 'image_project' ,
                    relation_id: this.id ,
                })
                .then((res) => {

                })
                .finally(() => {
                    this.pending('record' , true);
                });
        } ,

        initPicPreview () {
            var images = [];
            this.data.images.forEach((v) => {
                images.push(v.src);
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
            Api.imageProject.newest({
                limit: this.newest.limit ,
                type: this.newest.type ,
            } ,  (msg , data , code) => {
                this.pending('getNewestData' , false);
                if (res.code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.newest.data = data;
            });
        } ,

        // 获取推荐数据
        getRecommendData (){
            this.pending('getRecommendData' , true);
            Api.imageProject
                .recommend(this.id , {
                    limit: this.recommend.limit ,
                    type: this.recommend.type ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.message('error' , msg);
                        return ;
                    }
                    this.recommend.data = res.data;
                })
                .finally(() => {
                    this.pending('getRecommendData' , false);
                });
        } ,

        linkToImageProject (imageSubject) {
            const link = this.genUrl(`/image_project/${imageSubject.id}/show`);
            this.openWindow(link , '_self');
            this.reload();
        } ,

        focusHandle () {
            if (this.pending('focusHandle')) {
                return ;
            }
            this.pending('focusHandle' , true);
            Api.user
                .focusHandle(null , {
                    user_id: this.data.user_id ,
                    action: this.data.user.focused ? 0 : 1 ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
                            this.focusHandle();
                        });
                        return ;
                    }
                    this.getData();
                })
                .finally(() => {
                    this.pending('focusHandle' , false);
                });

        } ,
    } ,
}
