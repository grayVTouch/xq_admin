import mixin from './mixin.js';

const collections = {
    page: 1 ,
    total: 0 ,
    limit: TopContext.limit ,
    data: [] ,
};

const collectionGroupForm = {
    name: '' ,
};

const updateCollectionGroupForm = {
    collection_group_id: 0 ,
    name: '' ,
};

export default {
    name: "favorites" ,

    data () {
        return {
            // 表单搜索
            search: {
                relation_type: '',
                value: '',
            },

            relationType: {
                image_project: '图片专题',
                // video_subject: '视图专题',
                // article_subject: '文章专题',
                // bbs_subject: '论坛帖子',
            },

            favorites: [],

            dom: {},
            val: {
                pending: {},
                fixed: false,
            },

            collections: G.copy(collections, true),

            currentCollectionGroup: {} ,

            collectionGroupForm: {...collectionGroupForm} ,
            updateCollectionGroupForm: {...updateCollectionGroupForm} ,
        };
    } ,

    mounted () {
        this.$emit('focus-menu' , 'favorites');
        this.initDom();
        this.initEvent();
        this.getCollectionGroup((keep) => {
            if (!keep) {
                return ;
            }
            if (this.favorites.length > 0) {
                this.switchCollectionGroup(this.favorites[0]);
            }
        });
    } ,

    mixins: [
        mixin
    ] ,

    methods: {
        initDom () {
            this.dom.win = G(window);
            this.dom.filter = G(this.$refs.filter);
            this.dom.createForm = G(this.$refs['create-form']);
        } ,

        scrollEvent () {
            const scrollTop = this.dom.filter.getWindowOffsetVal('top');
            this.val.fixed = scrollTop < TopContext.val.fixedTop;
        } ,

        initEvent () {
            this.dom.win.on('scroll' , this.scrollEvent.bind(this));
        } ,

        getCollectionGroup (callback) {
            this.pending('getCollectionGroup' , true);
            Api.user.collectionGroup(this.search.then((res) => {
                this.pending('getCollectionGroup' , false);
                if (res.code !== TopContext.code.Success) {
                    G.invoke(callback , null , false);
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.getCollectionGroup();
                    });
                    return ;
                }
                this.favorites = data;
                G.invoke(callback , null , true);
            });
        } ,

        destroyCollectionGroup (collectionGroup , callback) {
            const pending = 'destroyCollectionGroup_' + collectionGroup.id;
            if (this.pending(pending)) {
                G.invoke(callback , null , false);
                return ;
            }
            this.pending(pending , true);
            Api.user.destroyAllCollectionGroup([collectionGroup.id].then((res) => {
                this.pending(pending , false);
                if (res.code !== TopContext.code.Success) {
                    G.invoke(callback , null , false);
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.destroyHistory(collectionGroup);
                    });
                    return ;
                }
                this.getCollectionGroup();
                G.invoke(callback , null , true);
            })
        } ,

        destroyCollectionGroupEvent (collectionGroup) {
            this.hideCollectionGroupAction(collectionGroup);
            this.destroyCollectionGroup(collectionGroup , (keep) => {
                if (!keep) {
                    return ;
                }
                if (this.currentCollectionGroup.id === collectionGroup.id) {
                    this.collections = G.copy(collections);
                }
            });
        } ,

        // 获取指定收藏夹下收藏的内容
        getCollections (collectionGroup) {
            this.pending('getCollections' , true);
            Api.user.collections({
                limit: this.collections.limit ,
                page: this.collections.page ,
                collection_group_id: collectionGroup.id ,
                relation_type: this.search.relation_type ,
            }.then((res) => {
                this.pending('getCollections' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.getCollections(collectionGroup);
                    });
                    return ;
                }
                data.data.forEach((collection) => {
                    collection.relation = collection.relation ? collection.relation : {};
                    switch (collection.relation_type)
                    {
                        case 'image_project':
                            this.handleImageProject(collection.relation);
                            break;
                    }
                });
                this.collections.page = data.current_page;
                this.collections.total = data.total;
                this.collections.data = data.data;
            });
        } ,

        toPage (page) {
            this.collections.page = page;
            this.getCollections(this.currentCollectionGroup);
        } ,

        switchCollectionGroup (collectionGroup) {
            this.currentCollectionGroup = collectionGroup;
            this.getCollections(collectionGroup);
        } ,

        showCollectionGroupAction (collectionGroup) {
            const actions = G(this.$refs['collection_group_actions_' + collectionGroup.id]);
            actions.removeClass('hide');
            actions.animate({
                opacity: 1 ,
                bottom: '0px' ,
            });
        } ,

        hideCollectionGroupAction (collectionGroup) {
            const actions = G(this.$refs['collection_group_actions_' + collectionGroup.id]);
            actions.animate({
                opacity: 0 ,
                bottom: '20px' ,
            } , () => {
                actions.addClass('hide');
            });
        } ,

        // 创建收藏夹
        createCollectionGroup () {
            if (this.pending('createCollectionGroup')) {
                return ;
            }
            this.pending('createCollectionGroup' , true);
            Api.user.createCollectionGroup(this.collectionGroupForm.then((res) => {
                this.pending('createCollectionGroup' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.createCollectionGroup();
                    });
                    return ;
                }
                this.getCollectionGroup();
                this.hideCreateFavoritesForm();
            });
        } ,

        // 删除收藏的内容
        destroyCollection (collection) {
            const pending = 'destroyCollection_' + collection.id;
            if (this.pending(pending)) {
                return ;
            }
            this.pending(pending , true);
            Api.user.destroyCollection({
                collection_id: collection.id ,
            }.then((res) => {
                this.pending(pending , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.createCollectionGroup();
                    });
                    return ;
                }
                this.getCollectionGroup();
                this.getCollections(this.currentCollectionGroup);
            });
        } ,

        showCreateFavoritesForm () {
            this.dom.createForm.removeClass('hide');
            this.dom.createForm.startTransition('show');
        } ,

        hideCreateFavoritesForm () {
            this.collectionGroupForm = {...collectionGroupForm};
            this.dom.createForm.endTransition('show' , () => {
                this.dom.createForm.addClass('hide');
            });
        } ,

        showUpdateFavoritesForm (collectionGroup) {
            this.updateCollectionGroupForm.collection_group_id = collectionGroup.id;
            this.updateCollectionGroupForm.name = collectionGroup.name;
            this.hideCollectionGroupAction(collectionGroup);
            const updateForm = G(this.$refs['update-form-' + collectionGroup.id]);
            updateForm.removeClass('hide');
            updateForm.startTransition('show');
        } ,

        hideUpdateFavoritesForm (collectionGroup) {
            this.updateCollectionGroupForm = {...updateCollectionGroupForm};
            const updateForm = G(this.$refs['update-form-' + collectionGroup.id]);
            updateForm.endTransition('show' , () => {
                updateForm.addClass('hide');
            });
        } ,

        updateCollectionGroup () {
            const pending = 'updateCollectionGroup_' + this.updateCollectionGroupForm.collection_group_id;
            if (this.pending(pending)) {
                return ;
            }
            this.pending(pending , true);
            Api.user.updateCollectionGroup(this.updateCollectionGroupForm.then((res) => {
                this.pending(pending , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.createCollectionGroup();
                    });
                    return ;
                }
                this.hideUpdateFavoritesForm(data);
                for (let i = 0; i < this.favorites.length; ++i)
                {
                    const cur = this.favorites[i];
                    if (cur.id === data.id) {
                        this.favorites.splice(i , 1 , data);
                    }
                }
            });
        } ,
    } ,
}
