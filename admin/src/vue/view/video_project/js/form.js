const form = {
    module_id: '' ,
    weight: 0 ,
    category_id: '' ,
    video_series_id: '' ,
    video_company_id: '' ,
    end_status: 'completed' ,
    tags: [] ,
    score: 0 ,
    count: 0 ,
    status: 0 ,
};

const users = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            center: TopContext.table.alignCenter ,
            width: TopContext.table.id ,
        } ,
        {
            title: '名称' ,
            key: 'username' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '头像' ,
            slot: 'avatar' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '创建时间' ,
            key: 'created_at' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
        } ,
    ] ,
    current: {
        id: 0 ,
        username: 'unknow' ,
    } ,
    limit: TopContext.limit ,
    value: '' ,
    page: 1 ,
    total: 0 ,
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
            key: 'created_at' ,
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
            key: 'created_at' ,
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
                drawer: false ,
                modalForVideoSeries: false ,
                modalForVideoCompany: false ,
                modalForUser: false ,
            } ,
            // 发布时间
            releaseDate: '' ,
            // 完结时间
            endDate: '' ,

            // 视频系列
            videoSeries: G.copy(videoSeries , true) ,

            // 视频制作公司
            videoCompany: G.copy(videoCompany , true) ,

            // 用户
            users: G.copy(users , true) ,

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
                    this.message('error' , msg);
                    return ;
                }
                this.modules = data;
            });
        } ,

        getTopTags () {
            Api.tag.topByModuleId(this.form.module_id , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.topTags = data;
            });
        } ,

        getCategories (callback) {
            this.pending('getCategories' , true);
            Api.category.search({
                module_id: this.form.module_id ,
                type: 'video_project' ,
            } , (msg , data , code) => {
                this.pending('getCategories' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
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
            this.val.error.module_id = '';
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
                Api.video_project.update(form.id , form , callback);
                return ;
            }
            Api.video_project.store(form , callback);
        } ,

        // 获取当前编辑记录详情
        findById (id) {
            return new Promise((resolve , reject) => {
                this._val('findById' , true);
                Api.video_project.show(id , (msg , data , code) => {
                    this._val('findById' , true);
                    if (code !== TopContext.code.Success) {
                        this.message('error' , msg);
                        reject();
                        return ;
                    }
                    data.score = parseFloat(data.score);
                    this.form = data;
                    resolve();
                });
            });
        } ,

        openFormDrawer () {
            this._val('drawer' , true);
            this.getModules();
            if (this.mode === 'edit') {
                this.getTopTags();
                this.getCategories();
                this.findById(this.data.id).then((res) => {
                    this.ins.thumb.render(this.form.thumb);
                    this.videoSeries.current    = this.form.video_series ? this.form.video_series : G.copy(videoSeries.current);
                    this.videoCompany.current   = this.form.video_company ? this.form.video_company : G.copy(videoCompany.current);
                    this.users.current          = this.form.user ? this.form.user : G.copy(users.current);
                    this.releaseDate            = this.form.release_date;
                    this.endDate                = this.form.end_date;
                });
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
            this.users = G.copy(users);
            this.videoCompany = G.copy(videoCompany);
            this.videoSeries = G.copy(videoSeries);
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
            this._val('modalForVideoSeries' , false);
            this.videoSeries.current = G.copy(row);
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
            this.videoCompany.current = G.copy(row);
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

        setReleaseDateEvent (date) {
            this.form.release_date = date;
        } ,

        setEndDateEvent (date) {
            this.form.end_date = date;
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
            Api.video_project.destroyTag({
                video_project_id: this.form.id ,
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
                user_id: this.form.user_id ,
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

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search({
                value: this.users.value ,
                limit: this.users.limit ,
                page: this.users.page ,
            }, (msg , data , code) => {
                this.pending('searchUser' , false);
                if (code !== TopContext.code.Success) {
                    this.error({user_id: data});
                    return ;
                }
                this.users.total = data.total;
                this.users.page = data.current_page;
                this.users.data = data.data;
            });
        } ,

        userPageEvent (page) {
            this.users.page = page;
            this.searchUser();
        } ,

        searchUserEvent (e) {
            this.searchUser();
            this._val('modalForUser' , true);
        } ,

        updateUserEvent (row , index) {
            this.error({user_id: ''}, false);
            this.form.user_id = row.id;
            this._val('modalForUser', false);
            this.users.data = [];
            this.users.current = G.copy(row);
        } ,
    } ,

    watch: {

    } ,

}
