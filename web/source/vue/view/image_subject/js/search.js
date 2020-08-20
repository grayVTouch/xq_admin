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

const subjects = {
    selected: [] ,
    selectedIds: [] ,
    data: [] ,
    page: 1 ,
    limit: TopContext.limit ,
    total: 0 ,
    value: '' ,
};

const images = {
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
            images: G.copy(images) ,

            // 分类
            categories: G.copy(categories) ,

            // 关联主体
            subjects: G.copy(subjects) ,

            // 标签
            tags: G.copy(tags) ,

            val: {
                categories: false ,
                subjects: true ,
                pending: {} ,
            } ,

            dom: {} ,
            ins: {} ,

            // 排序
            orders: [
                {
                    name: '上传日期' ,
                    key: 'create_time|desc' ,
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

        // 图片点赞 | 取消点赞
        praiseHandle (imageSubject) {
            if (this.pending('praiseHandle')) {
                return ;
            }
            const self = this;
            const action = imageSubject.praised ? 0 : 1;
            this.pending('praiseHandle' , true);
            Api.user.praiseHandle({
                relation_type: 'image_subject' ,
                relation_id: imageSubject.id ,
                action ,
            } , (msg , data , code) => {
                this.pending('praiseHandle' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code , () => {
                        this.praiseHandle(imageSubject);
                    });
                    return ;
                }
                this.handleImageSubject(data);
                for (let i = 0; i <  this.images.data.length; ++i)
                {
                    const cur = this.images.data[i];
                    if (cur.id === data.id) {
                        this.images.data.splice(i , 1 ,data);
                        break;
                    }
                }
            });
        } ,

        initQuery (query) {
            if (query.category_id) {
                // 选中该分类
                this.focusCategoryByCategoryId(query.category_id);
            }
            if (query.subject_id) {
                // 选中该主体
                this.focusSubjectBySubjectId(query.subject_id);
            }
            if (query.tag_id) {
                // 选中该主体
                this.focusTagByTagId(query.tag_id);
            }
        } ,

        focusCategoryByCategoryId (categoryId) {
            categoryId = parseInt(categoryId);
            Api.category.show(categoryId , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                if (this.categories.selectedIds.indexOf(categoryId) >= 0) {
                    return ;
                }
                this.categories.selected.push(data);
                this.categories.selectedIds.push(data.id);
                this.images.page = 1;
                this.getWithPager();
            });
        } ,

        focusSubjectBySubjectId (subjectId) {
            subjectId = parseInt(subjectId);
            Api.subject.show(subjectId , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                if (this.subjects.selectedIds.indexOf(subjectId) >= 0) {
                    return ;
                }
                this.subjects.selected.push(data);
                this.subjects.selectedIds.push(data.id);
                this.images.page = 1;
                this.getWithPager();
            });
        } ,

        focusTagByTagId (tagId) {
            tagId = parseInt(tagId);
            Api.tag.show(tagId , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                if (this.tags.selectedIds.indexOf(tagId) >= 0) {
                    return ;
                }
                data.tag_id = data.id;
                this.tags.selected.push(data);
                this.tags.selectedIds.push(data.id);
                this.images.page = 1;
                this.getWithPager();
            });
        } ,

        resetFilter () {
            this.categories = G.copy(categories);
            this.subjects = G.copy(subjects);
            this.tags = G.copy(tags);
            this.images.order = '';
            this.images.page = 1;
            this.getWithPager();
        } ,

        orderInImageSubject (v) {
            this.closeOrderSelectorInHorizontal();
            this.closeOrderSelectorInVertical();
            this.images.order = v.key;
            this.images.page = 1;
            this.getWithPager();
        } ,

        toPageInImageSubject (page) {
            this.images.page = page;
            this.getWithPager();
        } ,

        toPageInSubject (page) {
            this.subjects.page = page;
            this.getSubjects();
        } ,

        filterModeChangeEvent () {
            this.images.page = 1;
            this.getWithPager();
        } ,

        toPageInTag (page) {
            this.tags.page = page;
            this.getTags();
        } ,

        resetTagFilter () {
            this.tags = G.copy(tags , true);
            this.getTags();
            this.images.page = 1;
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
            this.images.page = 1;
            this.getWithPager();
        } ,

        delTag (tag) {
            const index = this.findIndexInTagsByTagId(tag.tag_id);
            this.tags.selected.splice(index , 1);
            this.tags.selectedIds.splice(index , 1);
            this.images.page = 1;
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
            this.images.page = 1;
            this.getWithPager();
        } ,

        delCategory (category) {
            const index = this.findIndexInCategoriesByCategoryId(category.id);
            this.categories.selected.splice(index , 1);
            this.categories.selectedIds.splice(index , 1);
            this.images.page = 1;
            this.getWithPager();
        } ,

        resetCategoryFilter () {
            this.categories = G.copy(categories);
            this.getCategories();
            this.images.page = 1;
            this.getWithPager();
        } ,

        // 检查分类是否存在
        findIndexInSubjectsBySubjectId (subjectId) {
            for (let i = 0; i < this.subjects.selected.length; ++i)
            {
                const cur = this.subjects.selected[i];
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
            this.subjects.selected.push(subject);
            this.subjects.selectedIds.push(subject.id);
            this.images.page = 1;
            this.getWithPager();
        } ,

        delSubject (subject) {
            const index = this.findIndexInCategoriesByCategoryId(subject.id);
            this.subjects.selected.splice(index , 1);
            this.subjects.selectedIds.splice(index , 1);
            this.images.page = 1;
            this.getWithPager();
        } ,

        searchSubject () {
            this.subjects.page = 1;
            this.getSubjects();
        } ,

        resetSubjectFilter () {
            this.subjects = G.copy(subjects);
            this.getSubjects();
            this.images.page = 1;
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
            this.subjects.page = 1;
            this.getSubjects();
        } ,

        closeSubjectSelector () {
            this.subjects.data = [];
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
            Api.image_subject.hotTagsWithPager({
                limit: this.tags.limit ,
                page:  this.tags.page ,
                value: this.tags.value ,
                type: this.tags.type ,
            } , (msg , data , code) => {
                this.pending('getTags' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.tags.page = data.current_page;
                this.tags.total = data.total;
                this.tags.data = data.data;
            });
        } ,

        // 获取分类数据
        getCategories () {
            this.pending('getCategories' , true);
            Api.image_subject.categories((msg , data , code) => {
                this.pending('getCategories' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.categories.data = data;
            })
        } ,

        // 获取关联主体
        getSubjects () {
            this.pending('getSubjects' , true);
            Api.image_subject.subjects({
                page: this.subjects.page ,
                limit: this.subjects.limit ,
                value: this.subjects.value ,
            } , (msg , data , code) => {
                this.pending('getSubjects' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.subjects.total = data.total;
                this.subjects.page = data.current_page;
                this.subjects.maxPage = data.last_page;
                this.subjects.data = data.data;
            })
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.categorySelector = G(this.$refs['category-selector']);
            this.dom.tagSelector = G(this.$refs['tag-selector']);
            this.dom.subjectSelector = G(this.$refs['subject-selector']);
            this.dom.orderSelector = G(this.$refs['order-selector']);
            this.dom.orderSelectorInHorizontal = G(this.$refs['order-selector-in-horizontal']);
            this.dom.orderSelectorInVertical = G(this.$refs['order-selector-in-vertical']);
            this.dom.filterSelectorInHorizontal = G(this.$refs['filter-selector-in-horizontal']);
            this.dom.filterFixedInSlidebar = G(this.$refs['filter-fiexed-in-slidebar']);


            this.dom.search = G(this.$parent.$refs['search']);
        } ,

        getWithPager () {
            this.pending('getWithPager' , true);
            Api.image_subject.index({
                page: this.images.page ,
                mode: this.tags.mode ,
                limit: this.images.limit ,
                category_ids: G.jsonEncode(this.categories.selectedIds) ,
                subject_ids: G.jsonEncode(this.subjects.selectedIds) ,
                tag_ids: G.jsonEncode(this.tags.selectedIds) ,
                value: this.images.value ,
                order: this.images.order ,
                type: this.images.type ,
            } , (msg , data , code) => {
                this.pending('getWithPager' , false);
                if (code !== TopContext.code.Success) {
                    return this.message('error' , msg);
                    return ;
                }
                this.images.page = data.current_page;
                this.images.total = data.total;
                this.images.data = data.data;
            });
        } ,

        searchEvent (e) {
            if (e.keyCode !== 13) {
                return ;
            }
            const tar = G(e.currentTarget);
            this.images.value = tar.val();
            this.images.page = 1;
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