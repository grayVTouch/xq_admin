const form = {
    module_id: '' ,
    weight: 0 ,
    category_id: '' ,
    video_series_id: '' ,
    video_company_id: '' ,
    status: 'completed' ,
    tags: [] ,
    score: 0 ,
    count: 0 ,
};

const videoSeries = {
    total: 0 ,
    page: 1 ,
    field: [
        {
            title: 'id' ,
            key: 'id' ,
        } ,
        {
            title: '名称' ,
            key: 'name' ,
        } ,
        {
            title: '创建时间' ,
            key: 'create_time' ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            align: TopContext.table.alignCenter ,
        } ,
    ] ,
    data: [] ,
    current: {
        id: 0 ,
        name: 'unknow' ,
    } ,
    limit: TopContext.limit ,
    value: '' ,
};

const videoCompany = {
    total: 0 ,
    page: 1 ,
    field: [
        {
            title: 'id' ,
            key: 'id' ,
        } ,
        {
            title: '名称' ,
            key: 'name' ,
        } ,
        {
            title: '创建时间' ,
            key: 'create_time' ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            align: TopContext.table.alignCenter ,
        } ,
    ] ,
    data: [] ,
    current: {
        id: 0 ,
        name: 'unknow' ,
    } ,
    limit: TopContext.limit ,
    value: '' ,
};

export default {
    name: "index",

    props: {
        data: {
            type: Object ,
            required: true ,
        } ,
        mode: {
            type: String ,
            required: true
        } ,
    } ,

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                error: {} ,
                selectedIds: [] ,
                drawer: false ,
                modalForVideoSeries: false ,
                modalForVideoCompany: false ,
            } ,
            releaseTime: '' ,
            endTime: '' ,
            table: {
                field: [
                    {
                        type: 'selection',
                        minWidth: TopContext.table.checkbox ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    },
                    {
                        title: 'id' ,
                        key: 'id' ,
                        minWidth: TopContext.table.id ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '名称' ,
                        slot: 'name' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '封面' ,
                        slot: 'thumb' ,
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '评分' ,
                        key: 'score' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '模块id',
                        key: 'module_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '视频系列' ,
                        slot: 'video_series_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '视频制作公司' ,
                        slot: 'video_company_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '视频数',
                        key: 'count',
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter,
                    },
                    // {
                    //     title: '播放数',
                    //     key: 'play_count',
                    //     minWidth: TopContext.table.number ,
                    //     align: TopContext.table.alignCenter,
                    // },
                    {
                        title: '描述' ,
                        key: 'description' ,
                        minWidth: TopContext.table.desc ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '状态' ,
                        slot: 'status' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                    {
                        title: '权重' ,
                        key: 'weight' ,
                        minWidth: TopContext.table.weight ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '发布时间' ,
                        key: 'release_time' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '完结时间' ,
                        key: 'end_time' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '创建时间' ,
                        key: 'create_time' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                        minWidth: TopContext.table.action ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                ] ,
                total: 0 ,
                page: 1 ,
                data: [] ,
            } ,

            // 视频系列
            videoSeries: G.copy(videoSeries , true) ,

            // 视频制作公司
            videoCompany: G.copy(videoCompany , true) ,

            // 搜索条件
            search: {
                limit: TopContext.limit
            } ,

            // 模块
            modules: [] ,

            // 当前修改事物
            form: G.copy(form , true) ,

            // 标签
            tags: [] ,

            // 标签排行榜
            topTags: [] ,

            // 分类
            categories: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    computed: {
        title () {
            return this.mode === 'edit' ? '编辑' : '添加';
        } ,

    } ,

    methods: {

        getModules () {
            this.pending('getModules' , true);
            Api.module.all((msg , data , code) => {
                this.pending('getModules' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.modules = data;
            });
        } ,

        getTopTags () {
            Api.tag.topByModuleId(this.form.module_id , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.topTags = data;
            });
        } ,

        getCategories (callback) {
            this.pending('getCategories' , true);
            Api.category.searchByModuleId(this.form.module_id , (msg , data , code) => {
                this.pending('getCategories' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    G.invoke(callback , null , false);
                    return ;
                }
                this.categories = data;
                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,

        // 模块发生变化的时候
        moduleChanged () {
            this.form.video_series_id = '';
            this.form.video_company_id = '';
            this.form.video_series = G.copy(videoSeries.current);
            this.form.video_company = G.copy(videoCompany.current);
            this.topTags = [];
            this.getTopTags();
            this.getCategories();
        },

        initDom () {
            this.dom.tagInput = G(this.$refs['tag-input']);
            this.dom.tagInputOuter = G(this.$refs['tag-input-outer']);
            this.dom.thumb = G(this.$refs.thumb);
        } ,

        initIns () {
            var self = this;
            this.ins.thumb = new Uploader(this.dom.thumb.get(0) , {
                api: this.thumbApi() ,
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.thumb = data.data;
                } ,
                cleared () {
                    self.form.thumb = '';
                } ,
            });
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (msg , data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.successHandle((keep) => {
                    this.$emit('on-success');
                    if (keep) {
                        return ;
                    }
                    this.closeFormDrawer();
                });
            };
            const form = G.copy(this.form);
            form.images = G.jsonEncode(this.images);
            form.tags = [];
            this.tags.forEach((v) => {
                form.tags.push(v.id);
            });
            form.tags = G.jsonEncode(form.tags);
            if (this.mode === 'edit') {
                Api.video_subject.update(form.id , form , callback);
                return ;
            }
            Api.video_subject.store(form , callback);
        } ,

        openFormDrawer () {
            this._val('drawer' , true);
            this.getModules();
            if (this.mode === 'edit') {
                this.getTopTags();
                this.getCategories();
            }

        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this._val('drawer' , false);
            // 清除所有错误信息
            this.error({});
            this.ins.thumb.clearAll();
            this.form = G.copy(form);
            this.tags = [];
            this.topTags = [];
            this.categories = [];

        } ,

        searchVideoSeries () {
            this.pending('searchVideoSeries' , true);
            Api.video_series.search({
                module_id: this.form.module_id ,
                value: this.videoSeries.value ,
                limit: this.videoSeries.limit ,
            } , (msg , data , code) => {
                this.pending('searchVideoSeries' , false);
                if (code !== TopContext.code.Success) {
                    this.error({subject_id: data});
                    return ;
                }
                this.videoSeries.page = data.current_page;
                this.videoSeries.total = data.total;
                this.videoSeries.data = data.data;
            });
        } ,

        updateVideoSeriesEvent (row , index) {
            this.error({video_series_id: ''} , false);
            this.form.video_series_id = row.id;
            this.form.video_series = row;
            this._val('modalForVideoSeries' , false);
            this.videoSeries.data = [];
        } ,

        searchVideoSeriesEvent (e) {
            if (this.form.module_id < 1) {
                this.error({video_series_id: '请选择模块后操作'});
                return ;
            }
            this._val('modalForVideoSeries' , true);
            this.searchVideoSeries();
        } ,

        videoSeriesPageEvent (page) {
            this.videoSeries.page = page;
            this.searchVideoSeries();
        } ,


        searchVideoCompany () {
            this.pending('searchVideoCompany' , true);
            Api.video_company.search({
                module_id: this.form.module_id ,
                value: this.videoCompany.value ,
                limit: this.videoCompany.limit ,
            } , (msg , data , code) => {
                this.pending('searchVideoCompany' , false);
                if (code !== TopContext.code.Success) {
                    this.error({video_company_id: data});
                    return ;
                }
                this.videoCompany.page = data.current_page;
                this.videoCompany.total = data.total;
                this.videoCompany.data = data.data;
            });
        } ,

        updateVideoCompanyEvent (row , index) {
            this.error({video_company_id: ''} , false);
            this.form.video_company_id = row.id;
            this.form.video_company = row;
            this._val('modalForVideoCompany' , false);
            this.videoCompany.data = [];
        } ,

        searchVideoCompanyEvent (e) {
            if (this.form.module_id < 1) {
                this.error({video_company_id: '请选择模块后操作'});
                return ;
            }
            this.searchVideoCompany();
            this._val('modalForVideoCompany' , true);
        } ,

        videoCompanyPageEvent (page) {
            this.videoCompany.page = page;
            this.searchVideoCompany();
        } ,

        setReleaseTimeEvent (date) {
            this.form.release_time = date;
        } ,

        setEndTimeEvent (date) {
            this.form.end_time = date;
        } ,

        isExistTagByTagId (tagId) {
            for (let i = 0; i < this.form.tags.length; ++i)
            {
                const cur = this.form.tags[i];
                if (tagId === cur.tag_id) {
                    return true;
                }
            }
            for (let i = 0; i < this.tags.length; ++i)
            {
                const cur = this.tags[i];
                if (tagId === cur.id) {
                    return true;
                }
            }
            return false;
        } ,

        isExistTagByName (name) {
            const tags = this.form.tags.concat(this.tags);
            for (let i = 0; i < tags.length; ++i)
            {
                const cur = tags[i];
                if (name === cur.name) {
                    return true;
                }
            }
            return false;
        } ,

        appendTag (v) {
            if (this.isExistTagByTagId(v.id)) {
                this.message('error' , '标签已经存在');
                return ;
            }
            this.tags.push(v);
        } ,

        destroyTag (tagId , direct = true) {
            if (direct) {
                for (let i = 0; i < this.tags.length; ++i)
                {
                    const tag = this.tags[i];
                    if (tag.id === tagId) {
                        this.tags.splice(i , 1);
                        i--;
                    }
                }
                return ;
            }
            // 编辑模式
            this.pending('destroy_tag_' + tagId , true);
            Api.video_subject.destroyTag({
                video_subject_id: this.form.id ,
                tag_id: tagId ,
            } , (msg , data , code) => {
                this.pending('destroy_tag_' + tagId , false);
                if (code !== TopContext.code.Success) {
                    this.error({tags: data});
                    return ;
                }
                for (let i = 0; i < this.form.tags.length; ++i)
                {
                    const tag = this.form.tags[i];
                    if (tag.tag_id === tagId) {
                        this.form.tags.splice(i , 1);
                        i--;
                    }
                }
            });
        } ,

        createOrAppendTag () {
            this.val.error.tags = '';
            const name = this.dom.tagInput.text().replace(/\s/g , '');
            this.dom.tagInput.html(name);
            if (!G.isValid(name)) {
                this.message('error' , '请提供标签名称');
                return ;
            }
            if (this.isExistTagByName(name)) {
                this.message('error' , '标签已经存在');
                return ;
            }
            this.dom.tagInput.origin('blur');
            this.dom.tagInputOuter.addClass('disabled');
            Api.tag.findOrCreateTag({
                name ,
                module_id: this.form.module_id ,
            } , (msg , data , code) => {
                this.dom.tagInputOuter.removeClass('disabled');
                if (code !== TopContext.code.Success) {
                    this.error({tags: msg} , false);
                    return ;
                }
                this.tags.push(data);
                this.dom.tagInput.html('');
            });
        } ,
    } ,

    watch: {
        data (data) {
            if (G.isEmptyObject(data)) {
                this.form = G.copy(form);
            } else {
                this.form = data;
            }
            // 仅在外界传入的时候初始化图片
            this.ins.thumb.render(this.form.thumb);
        } ,

        form: {
            deep: true ,
            handler (form) {
                this.videoSeries.current = form.video_series ? form.video_series : G.copy(videoSeries.current);
                this.videoCompany.current = form.video_company ? form.video_company : G.copy(videoCompany.current);
                this.releaseTime = form.release_time;
                this.endTime = form.end_time;
            },
        }
    } ,
}
