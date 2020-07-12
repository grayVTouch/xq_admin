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
                page: 1 ,
                maxPage: 1 ,
                data: [] ,
                total: 0 ,
                limit: 5 ,
            } ,

            dom: {} ,
            ins: {} ,
            val: {
                fixedMisc: false ,
            } ,
            // 收藏夹列表
            favorites: [] ,

            // 收藏夹表单
            collectionGroup: {
                relation_type: 'image_subject' ,
                relation_id: this.id ,
                name: '' ,
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
    } ,
    //
    // beforeRouteUpdate (to , from , next) {
    //     console.log('hello');
    // } ,
    //
    // beforeRouteEnter (to , from , next) {
    //     console.log('route enter');
    //     next((vm) => {
    //         vm.getData();
    //
    //     });
    // } ,

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
            } , (data , code) => {
                this.pending('praiseHandle' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(data , code , () => {
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
            } , (data , code) => {
                this.pending('getFavorites' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(data , code , () => {
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
            Api.user.createAndJoinCollectionGroup(this.collectionGroup , (data , code) => {
                this.pending('createAndJoinCollectionGroup' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(data, code , () => {
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
            } , (data , code) => {
                this.pending(pending , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(data , code , () => {
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
            Api.image_subject.show(this.id , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.pending('getData' , false);
                    this.message(data);
                    return ;
                }
                data.user = data.user ? data.user : {};
                data.subject = data.subject ? data.subject : {};
                data.images = data.images ? data.images : [];
                data.tags = data.tags ? data.tags : [];
                data.module = data.module ? data.module : [];

                this.data = {...data};
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
            this.ins.picPreview = new PicPreview(this.dom.picPreviewContainer.get(0) , {

            });
        },

        imageClick (index) {
            this.ins.picPreview.show(parseInt(index));
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.html = G(document.documentElement);
            this.dom.imageSubject = G(this.$refs['image-subject']);
            this.dom.picPreviewContainer = G(this.$refs['pic-preview-container']);
            this.dom.myFavorites = G(this.$refs['my-favorites']);
            this.dom.misc = G(this.$refs['misc']);
        },

        scrollEvent (e) {
            const y = window.pageYOffset;
            const clientH = this.dom.html.clientHeight();
            const maxHeight = 122 + this.dom.imageSubject.scrollHeight();
            if (y + clientH < maxHeight) {
                return ;
            }
            if (this.images.page >= this.images.maxPage) {
                return ;
            }
            this.images.page++;
            const start = (this.images.page - 1) * this.images.limit;
            const end = start + this.images.limit;
            this.images.data = this.images.data.concat(this.data.images.slice(start , end));
        } ,

        scrollWithMiscEvent () {
            const scrollTop = this.dom.misc.getWindowOffsetVal('top');
            if (scrollTop >= 0) {
                this._val('fixedMisc' , false);
            } else {
                this._val('fixedMisc' , true);
            }
        } ,

        initEvent () {
            this.dom.win.on('scroll' , this.scrollEvent.bind(this));
            this.dom.win.on('scroll' , this.scrollWithMiscEvent.bind(this));
        } ,
    } ,
}