const allHotTags = {
    page: 1 ,
    limit: TopContext.limit ,
    data: [] ,
    total: 0 ,
    value: '' ,
};

const images = {
    page: 1 ,
    maxPage: 1 ,
    limit: TopContext.limit ,
    data: [] ,
    total: 0 ,
    end: false ,
};

const search = {
    mode: 'loose' ,
    tags: [] ,
    tagIds: [] ,
};

export default {
    name: "image_subject" ,
    data () {
        return {

            // 热门标签（部分）
            partHotTags: [] ,
            // 所有标签
            allHotTags: {...allHotTags} ,
            // 图片
            images: {...images} ,
            // 焦点图片
            imageSubject: [] ,
            curTag: '' ,
            dom: {} ,
            val: {
                pending: {} ,
                tagSelectorInSlidebar: false ,
            } ,
            search: {...search} ,
        };
    } ,

    mounted () {
        this.initDom();
        this.getImageSubject();
        this.hotTags();
        this.newestInImageSubject();
        this.initEvent();
        // 调试阶段打开
        // this.showTagSelector();
    } ,

    methods: {

        // 图片点赞
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

        findInSelectedTagsByTagId (tagId) {
            for (let i = 0; i < this.search.tags.length; ++i)
            {
                const cur = this.search.tags[i];
                if (cur.tag_id === tagId) {
                    return cur;
                }
            }
            return false;
        } ,

        findIndexInSelectedTagsByTagId (tagId) {
            for (let i = 0; i < this.search.tags.length; ++i)
            {
                const cur = this.search.tags[i];
                if (cur.tag_id === tagId) {
                    return i;
                }
            }
            return false;
        } ,

        findIndexInSelectedTagIdsByTagId (tagId) {
            for (let i = 0; i < this.search.tagIds.length; ++i)
            {
                const id = this.search.tagIds[i];
                if (id === tagId) {
                    return i;
                }
            }
            return false;
        } ,

        filterByTag (tag) {
            const index = this.findIndexInSelectedTagsByTagId(tag.tag_id);
            if (index === false) {
                this.search.tags.push(tag);
                this.search.tagIds.push(tag.tag_id);
                this._val('selected_tag_' + tag.tag_id , true);
            } else {
                this.search.tags.splice(index , 1);
                this.search.tagIds.splice(index , 1);
                this._val('selected_tag_' + tag.tag_id , false);
            }
            if (this.search.tags.length === 0) {
                this.newestInImageSubject(true);
                return ;
            }
            this.getWithPagerInImageSubject(this.search.tagIds , this.search.mode , true);
        } ,

        resetTagFilter () {
            this.search.tags = [];
            this.search.tagIds = [];
            this.newestInImageSubject(true);
        } ,

        unselectedTagByTagId (tagId) {
            const index = this.findIndexInSelectedTagsByTagId(tagId);
            if (index === false) {
                return ;
            }
            this._val('selected_tag_' + tagId , false);
            this.search.tags.splice(index , 1);
            this.search.tagIds.splice(index , 1);
            if (this.search.tags.length === 0) {
                this.newestInImageSubject(true);
                return ;
            }
            this.getWithPagerInImageSubject(this.search.tagIds , this.search.mode , true);
        } ,

        filterModeChangeEvent (mode) {
            this.search.mode = mode;
            if (this.search.tags.length === 0) {
                return ;
            }
            this.getWithPagerInImageSubject(this.search.tagIds , this.search.mode , true);
        } ,

        tagPageEvent (page) {
            this.allHotTags.page = page;
            this.hotTagsWithPager();
        } ,

        // 显示标签选择器
        showTagSelector () {
            this.dom.tagSelector.removeClass('hide');
            this.dom.tagSelector.startTransition('show');
            this.allHotTags.page = 1;
            this.allHotTags = {...allHotTags};
            this.hotTagsWithPager();
        } ,

        closeTagSelector () {
            this.dom.tagSelector.endTransition('show' , () => {
                this.dom.tagSelector.addClass('hide');
            });
        } ,

        getImageSubject () {
            Api.image_subject.imageSubject((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.imageSubject = data;
            });
        } ,

        // 图片-最新图片
        newestInImageSubject (override = true) {
            if (override) {
                this.pending('switchImages' , true);
                this.images.page = 1;
            }
            this.pending('images' , true);
            this.curTag = 'newest';
            this.images.end = false;
            Api.image_subject.newestWithPager({
                limit: this.images.limit ,
                page:  this.images.page ,
            } , (msg , data , code) => {
                this.pending('switchImages' , false);
                if (code !== TopContext.code.Success) {
                    this.pending('images' , false);
                    this.message(msg);
                    return ;
                }
                this.images.page = data.current_page;
                this.images.maxPage = data.last_page;
                this.images.total = data.total;
                this.images.end = this.images.page === this.images.maxPage;

                // console.log('获取到的新增数据' , data.data);

                if (override) {
                    this.images.data = data.data;
                } else {
                    this.images.data = this.images.data.concat(data.data);
                }
                this.$nextTick(() => {
                    this.pending('images' , false);
                });
            });
        } ,

        hotInImageSubject (override = true) {
            if (override) {
                this.pending('switchImages' , true);
                this.images.page = 1;
            }
            this.curTag = 'hot';
            this.pending('images' , true);
            this.images.end = false;
            Api.image_subject.hotWithPager({
                limit: this.images.limit ,
                page:  this.images.page ,
            } , (msg , data , code) => {
                this.pending('switchImages' , false);
                if (code !== TopContext.code.Success) {
                    this.pending('images' , false);
                    this.message(msg);
                    return ;
                }
                this.images.page = data.current_page;
                this.images.maxPage = data.last_page;
                this.images.total = data.total;
                this.images.end = this.images.page === this.images.maxPage;
                if (override) {
                    this.images.data = data.data;
                } else {
                    this.images.data = this.images.data.concat(data.data);
                }
                this.$nextTick(() => {
                    this.pending('images' , false);
                });
            });
        } ,

        // 图片-热门标签
        hotTags () {
            Api.image_subject.hotTags({
                limit: 10 ,
            } , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.partHotTags = data;
            });
        } ,

        /**
         * 图片-按标签分类获取的图片
         * @param mode strict-严格的 |loose-宽松的
         * @param tagIds
         */
        getWithPagerInImageSubject (tagIds , mode , override = true) {
            if (override) {
                this.pending('switchImages' , true);
                this.images.page = 1;
            }
            this.pending('images' , true);
            this.images.end = false;
            Api.image_subject.getWithPagerByTagIds({
                mode ,
                tag_ids: G.jsonEncode(tagIds) ,
                limit: this.images.limit ,
                page: this.images.page ,
            } , (msg , data , code) => {
                this.pending('switchImages' , false);
                if (code !== TopContext.code.Success) {
                    this.pending('images' , false);
                    this.message(msg);
                    return ;
                }
                this.images.page = data.current_page;
                this.images.maxPage = data.last_page;
                this.images.total = data.total;
                this.images.end = this.images.page === this.images.maxPage;
                if (override) {
                    this.images.data = data.data;
                } else {
                    this.images.data = this.images.data.concat(data.data);
                }
                this.$nextTick(() => {
                    this.pending('images' , false);
                });
            });
        } ,

        /**
         * *********************
         * 图片标签
         * *********************
         */
        getWithPagerByTagIdInImageSubject (tagId , override = true) {
            this.search = {...search};
            this.curTag = 'tag_' + tagId;
            this.getWithPagerInImageSubject([tagId] , 'strict' , override);
        } ,

        // 图片-按标签分类获取的图片
        hotTagsWithPager () {
            this.pending('hotTagsWithPager' , true);
            Api.image_subject.hotTagsWithPager({
                limit: this.allHotTags.limit ,
                page:  this.allHotTags.page ,
                value: this.allHotTags.value ,
            } , (msg , data , code) => {
                this.pending('hotTagsWithPager' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.allHotTags.page = data.current_page;
                this.allHotTags.total = data.total;
                this.allHotTags.data = data.data;
            });
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.html = G(document.documentElement);
            this.dom.body = G(document.body);
            this.dom.tagSelector = G(this.$refs['tag-selector']);
            this.dom.tagSelectorInDocs = G(this.$refs['tags-selector-in-docs']);
            this.dom.tagSelectorInSlidebar = G(this.$refs['tags-selector-in-slidebar']);
        } ,

        initEvent () {
            this.dom.win.on('scroll' , this.winScrollEvent.bind(this));
            this.dom.win.on('scroll' , this.tagSelectorVisibleEvent.bind(this));
        } ,

        tagSelectorVisibleEvent (e) {
            const docY = this.dom.tagSelectorInDocs.getWindowOffsetVal('top');
            this._val('tagSelectorInSlidebar' , docY < 0);
            if (docY < 0) {
                this.dom.tagSelectorInSlidebar.removeClass('hide');
                this.dom.tagSelectorInSlidebar.startTransition('show');
            } else {
                this.dom.tagSelectorInSlidebar.endTransition('show' , () => {
                    this.dom.tagSelectorInSlidebar.addClass('hide');
                });
            }
        } ,

        winScrollEvent () {
            const y = window.pageYOffset;
            const scrollHeight = this.dom.html.scrollHeight();
            const clientHeight = this.dom.html.clientHeight();
            if (this.images.page >= this.images.maxPage) {
                return ;
            }
            if (clientHeight + y < scrollHeight) {
                return ;
            }
            if (this.pending('images')) {
                return ;
            }
            this.images.page++;
            switch (this.curTag)
            {
                case 'newest':
                    this.newestInImageSubject(false);
                    break;
                case 'hot':
                    this.hotInImageSubject(false);
                    break;
                case 'more':
                    this.getWithPagerInImageSubject(this.search.tagIds , this.search.mode , false);
                    break;
                default:
                    let tagId = parseInt(this.curTag.replace('tag_' , ''));
                    this.getWithPagerByTagIdInImageSubject(tagId , false);
                    break;
            }
        } ,
    } ,
}