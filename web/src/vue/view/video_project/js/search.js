const categories = {
    selected: [] ,
    selectedIds: [] ,
    data: [] ,
};


const tags = {
    selected: [] ,
    selectedIds: [] ,
    data: [] ,
    page: 1 ,
    limit: TopContext.limit ,
    total: 1 ,
    mode: 'strict' ,
    value: '' ,
    type: 'pro' ,
};

const videoSubjects = {
    selected: [] ,
    selectedIds: [] ,
    data: [] ,
    page: 1 ,
    limit: TopContext.limit ,
    total: 0 ,
    value: '' ,
};

const videoProjects = {
    data: [] ,
    value: '' ,
    limit: TopContext.limit ,
    total: 0 ,
    page: 1 ,
    order: '' ,
    type: 'pro' ,
};

export default {
    name: "search" ,

    data () {
        return {
            // 图片专题
            videoProjects: G.copy(videoProjects) ,

            // 分类
            categories: G.copy(categories) ,

            // 关联主体
            videoSubjects: G.copy(videoSubjects) ,

            // 标签
            tags: G.copy(tags) ,

            val: {
                categories: false ,
                videoSubjects: true ,
                pending: {} ,
            } ,

            dom: {} ,
            ins: {} ,

            // 排序
            orders: [
                {
                    name: '上传日期' ,
                    key: 'created_at|desc' ,
                } ,
                {
                    name: '点赞数量' ,
                    key: 'praise_count|desc' ,
                } ,
                {
                    name: '查看次数' ,
                    key: 'view_count|desc' ,
                } ,
            ] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initEvent();
        this.getWithPager();
    } ,

    beforeRouteEnter (to , from , next) {
        next((vm) => {
           vm.initQuery(to.query);
        });
    } ,

    beforeRouteUpdate  (to , from , next) {
        this.initQuery(to.query);
        next();
    } ,
    methods: {

        initQuery (query) {
            if (query.category_id) {
                // 选中该分类
                this.focusCategoryByCategoryId(query.category_id);
            }
            if (query.tag_id) {
                // 选中该主体
                this.focusTagByTagId(query.tag_id);
            }
        } ,

        focusCategoryByCategoryId (categoryId) {
            categoryId = parseInt(categoryId);
            this.pending('focusCategoryByCategoryId' , true);
            Api.category
                .show(categoryId)
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message);
                        return ;
                    }
                    if (this.categories.selectedIds.indexOf(categoryId) >= 0) {
                        return ;
                    }
                    const data = res.data;
                    this.categories.selected.push(data);
                    this.categories.selectedIds.push(data.id);
                    this.videoProjects.page = 1;
                    this.getWithPager();
                })
                .finally(() => {
                    this.pending('focusCategoryByCategoryId' , false);
                });
        } ,

        focusTagByTagId (tagId) {
            tagId = parseInt(tagId);
            Api.tag
                .show(tagId)
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.message('error' , res.message);
                        return ;
                    }
                    if (this.tags.selectedIds.indexOf(tagId) >= 0) {
                        return ;
                    }
                    const data = res.data;
                    data.tag_id = data.id;
                    this.tags.selected.push(data);
                    this.tags.selectedIds.push(data.id);
                    this.videoProjects.page = 1;
                    this.getWithPager();
                })
                .finally(() => {

                });
        } ,

        resetFilter () {
            this.categories = G.copy(categories);
            this.videoSubjects = G.copy(videoSubjects);
            this.tags = G.copy(tags);
            this.videoProjects.order = '';
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        orderInImageProject (v) {
            this.closeOrderSelectorInHorizontal();
            this.closeOrderSelectorInVertical();
            this.videoProjects.order = v.key;
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        toPageInImageProject (page) {
            this.videoProjects.page = page;
            this.getWithPager();
        } ,

        toPageInSubject (page) {
            this.videoSubjects.page = page;
            this.getSubjects();
        } ,

        filterModeChangeEvent () {
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        toPageInTag (page) {
            this.tags.page = page;
            this.getTags();
        } ,

        resetTagFilter () {
            this.tags = G.copy(tags , true);
            this.getTags();
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        // 检查分类是否存在
        findIndexInTagsByTagId (tagId) {
            for (let i = 0; i < this.tags.selected.length; ++i)
            {
                const cur = this.tags.selected[i];
                if (cur.tag_id === tagId) {
                    return i;
                }
            }
            return false;
        } ,

        // 根据分类对内容进行过滤
        filterByTag (tag) {
            const index = this.findIndexInTagsByTagId(tag.tag_id);
            if (index === false) {
                this.addTag(tag);
            } else {
                this.delTag(tag);
            }
        } ,

        addTag (tag) {
            const index = this.findIndexInTagsByTagId(tag.id);
            this.tags.selected.push(tag);
            this.tags.selectedIds.push(tag.tag_id);
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        delTag (tag) {
            const index = this.findIndexInTagsByTagId(tag.tag_id);
            this.tags.selected.splice(index , 1);
            this.tags.selectedIds.splice(index , 1);
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        searchTag () {
            this.tags.page = 1;
            this.getTags();
        } ,

        // 检查分类是否存在
        findIndexInCategoriesByCategoryId (categoryId) {
            for (let i = 0; i < this.categories.selected.length; ++i)
            {
                const cur = this.categories.selected[i];
                if (cur.id === categoryId) {
                    return i;
                }
            }
            return false;
        } ,

        // 根据分类对内容进行过滤
        filterByCategory (category) {
            const index = this.findIndexInCategoriesByCategoryId(category.id);
            if (index === false) {
                this.addCategory(category);
            } else {
                this.delCategory(category);
            }
        } ,

        addCategory (category) {
            const index = this.findIndexInCategoriesByCategoryId(category.id);
            this.categories.selected.push(category);
            this.categories.selectedIds.push(category.id);
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        delCategory (category) {
            const index = this.findIndexInCategoriesByCategoryId(category.id);
            this.categories.selected.splice(index , 1);
            this.categories.selectedIds.splice(index , 1);
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        resetCategoryFilter () {
            this.categories = G.copy(categories);
            this.getCategories();
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        // 检查分类是否存在
        findIndexInSubjectsBySubjectId (subjectId) {
            for (let i = 0; i < this.videoSubjects.selected.length; ++i)
            {
                const cur = this.videoSubjects.selected[i];
                if (cur.id === subjectId) {
                    return i;
                }
            }
            return false;
        } ,


        // 根据分类对内容进行过滤
        filterBySubject (subject) {
            const index = this.findIndexInSubjectsBySubjectId(subject.id);
            if (index === false) {
                this.addSubject(subject);
            } else {
                this.delSubject(subject);
            }
        } ,

        addSubject (subject) {
            const index = this.findIndexInCategoriesByCategoryId(subject.id);
            this.videoSubjects.selected.push(subject);
            this.videoSubjects.selectedIds.push(subject.id);
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        delSubject (subject) {
            const index = this.findIndexInCategoriesByCategoryId(subject.id);
            this.videoSubjects.selected.splice(index , 1);
            this.videoSubjects.selectedIds.splice(index , 1);
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        searchSubject () {
            this.videoSubjects.page = 1;
            this.getSubjects();
        } ,

        resetSubjectFilter () {
            this.videoSubjects = G.copy(videoSubjects);
            this.getSubjects();
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        showOrderSelectorInHorizontal () {
            this.dom.orderSelectorInHorizontal.removeClass('hide');
            this.dom.orderSelectorInHorizontal.startTransition('show');
        } ,

        closeOrderSelectorInHorizontal () {
            this.dom.orderSelectorInHorizontal.endTransition('show' , () => {
                this.dom.orderSelectorInHorizontal.addClass('hide');
            });
        } ,

        showOrderSelectorInVertical () {
            this.dom.orderSelectorInVertical.removeClass('hide');
            this.dom.orderSelectorInVertical.startTransition('show');
        } ,

        closeOrderSelectorInVertical () {
            this.dom.orderSelectorInVertical.endTransition('show' , () => {
                this.dom.orderSelectorInVertical.addClass('hide');
            });
        } ,

        showCategorySelector () {
            this.getCategories();
            this.dom.categorySelector.removeClass('hide');
            this.dom.categorySelector.startTransition('show');
        } ,

        closeCategorySelector () {
            this.categories.data = [];
            this.dom.categorySelector.endTransition('show' , () => {
                this.dom.categorySelector.addClass('hide');
            });
        } ,

        showSubjectSelector () {
            this.dom.subjectSelector.removeClass('hide');
            this.dom.subjectSelector.startTransition('show');
            this.videoSubjects.page = 1;
            this.getSubjects();
        } ,

        closeSubjectSelector () {
            this.videoSubjects.data = [];
            this.dom.subjectSelector.endTransition('show' , () => {
                this.dom.subjectSelector.addClass('hide');
            });
        } ,

        // 显示标签选择器
        showTagSelector () {
            this.dom.tagSelector.removeClass('hide');
            this.dom.tagSelector.startTransition('show');
            this.tags.page = 1;
            this.getTags();
        } ,

        closeTagSelector () {
            this.dom.tagSelector.endTransition('show' , () => {
                this.dom.tagSelector.addClass('hide');
            });
        } ,

        // 图片-按标签分类获取的图片
        getTags () {
            this.pending('getTags' , true);
            Api.videoProject
                .hotTagsWithPager({
                    limit: this.tags.limit ,
                    page:  this.tags.page ,
                    value: this.tags.value ,
                    type: this.tags.type ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message);
                        return ;
                    }
                    const data = res.data;
                    this.tags.page = data.current_page;
                    this.tags.total = data.total;
                    this.tags.data = data.data;
                })
                .finally(() => {
                    this.pending('getTags' , false);
                });
        } ,

        // 获取分类数据
        getCategories () {
            this.pending('getCategories' , true);
            Api.videoProject
                .categories()
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message);
                        return ;
                    }
                    this.categories.data = res.data;
                })
                .finally(() => {
                    this.pending('getCategories' , false);
                });
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.categorySelector = G(this.$refs['category-selector']);
            this.dom.tagSelector = G(this.$refs['tag-selector']);
            this.dom.subjectSelector = G(this.$refs['image-subject-selector']);
            this.dom.orderSelector = G(this.$refs['order-selector']);
            this.dom.orderSelectorInHorizontal = G(this.$refs['order-selector-in-horizontal']);
            this.dom.orderSelectorInVertical = G(this.$refs['order-selector-in-vertical']);
            this.dom.filterSelectorInHorizontal = G(this.$refs['filter-selector-in-horizontal']);
            this.dom.filterFixedInSlidebar = G(this.$refs['filter-fiexed-in-slidebar']);

            this.dom.search = G(this.$parent.$refs['search']);
        } ,

        getWithPager () {
            this.pending('getWithPager' , true);
            Api.videoProject
                .index({
                    page: this.videoProjects.page ,
                    mode: this.tags.mode ,
                    limit: this.videoProjects.limit ,
                    category_ids: G.jsonEncode(this.categories.selectedIds) ,
                    tag_ids: G.jsonEncode(this.tags.selectedIds) ,
                    value: this.videoProjects.value ,
                    order: this.videoProjects.order ,
                    type: this.videoProjects.type ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message);
                    }
                    const data = res.data;
                    this.videoProjects.page = data.current_page;
                    this.videoProjects.total = data.total;
                    this.videoProjects.data = data.data;
                })
                .finally(() => {
                    this.pending('getWithPager' , false);
                });
        } ,

        searchEvent (e) {
            if (e.keyCode !== 13) {
                return ;
            }
            const tar = G(e.currentTarget);
            this.videoProjects.value = tar.val();
            this.videoProjects.page = 1;
            this.getWithPager();
        } ,

        scrollEvent () {
            const y = this.dom.filterSelectorInHorizontal.getWindowOffsetVal('top');
            // console.log('y' , y);
            if (y > 0) {
                this.dom.filterFixedInSlidebar.endTransition('show' , () => {
                    this.dom.filterFixedInSlidebar.addClass('hide');
                });
            } else {
                this.dom.filterFixedInSlidebar.removeClass('hide');
                this.dom.filterFixedInSlidebar.startTransition('show');
            }
        } ,


        initEvent () {
            this.dom.win.on('click' , this.closeOrderSelectorInVertical.bind(this));
            this.dom.search.on('keyup' , this.searchEvent.bind(this));
            this.dom.win.on('scroll' , this.scrollEvent.bind(this));
        } ,
    } ,
}
