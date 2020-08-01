const users = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            center: TopContext.table.alignCenter ,
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
            key: 'create_time' ,
            center: TopContext.table.alignCenter ,
        } ,
    ] ,
    current: {} ,
    limit: TopContext.limit ,
    value: '' ,
    page: 1 ,
    total: 0 ,
};

const video_subjects = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '名称' ,
            key: 'name' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '模块【id】' ,
            slot: 'module_id' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '封面' ,
            slot: 'thumb' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '创建时间' ,
            key: 'create_time' ,
            center: TopContext.table.alignCenter ,
        } ,
    ] ,
    current: {} ,
    total: 0 ,
    page: 1 ,
    value: '' ,
};

export default {
    data () {
        return {
            val: {
                tab: 'base' ,
                pending: {} ,
                error: {} ,
                selectedIds: [] ,
                modalForSubject: false ,
                modalForUser: false ,
                valueForUser: '' ,
            } ,

            drawer: false ,

            // 用户
            users: G.copy(users , true),

            // 关联主体
            video_subjects: G.copy(video_subjects , true),

            // 标签
            tags: [] ,

            // 标签
            dom: {} ,

            // 实例
            ins: {} ,

            videos: {
                field: [
                    // {
                    //     type: 'selection' ,
                    //     width: TopContext.table.checkbox ,
                    //     center: TopContext.table.alignCenter ,
                    // } ,
                    {
                        title: 'id' ,
                        key: 'id' ,
                        center: TopContext.table.alignCenter ,
                        width: TopContext.table.id ,
                    } ,
                    {
                        title: '清晰度' ,
                        key: 'definition' ,
                        center: TopContext.table.alignCenter ,
                        width: TopContext.table.name ,
                    } ,
                    {
                        title: '视频' ,
                        slot: 'src' ,
                        // width: TopContext.table.video ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                        width: TopContext.table.action ,
                    } ,
                ] ,
                data: [] ,
            } ,

            images: [] ,

            createTime: '' ,
        };
    } ,

    model: {
        name: 'value' ,
        event: 'change' ,
    } ,

    props: {
        title: {
            type: String ,
            default: '' ,
        } ,
        value: {
            type: Boolean ,
            default: false
        } ,
        form: {
            type: Object ,
            default () {
                return {};
            } ,
        } ,
        categories: {
            type: Array ,
            default () {
                return [];
            } ,
        } ,
        modules: {
            type: Array ,
            default () {
                return [];
            } ,
        } ,
        topTags: {
            type: Array ,
            default () {
                return [];
            } ,
        } ,
        mode: {
            type: String ,
            default: '' ,
        } ,
    } ,

    mounted () {
        this.initDom();
        this.initIns();

    } ,

    methods: {

        moduleChangedEvent (moduleId) {
            moduleId = parseInt(moduleId);
            this.val.error.module_id = '';
            this.form.category_id = 0;
            this.form.video_subject_id = 0;
            this.form.topTags = [];

            this.$emit('on-module-change' , moduleId);
        } ,

        initDom () {
            this.dom.tagInput = G(this.$refs['tag-input']);
            this.dom.tagInputOuter = G(this.$refs['tag-input-outer']);
            this.dom.thumb = G(this.$refs.thumb);
            this.dom.video = G(this.$refs.video);
        } ,

        initIns () {
            const self = this;
            this.ins.thumb = new Uploader(this.dom.thumb.get(0) , {
                api: this.thumbApi(),
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

            this.ins.video = new Uploader(this.dom.video.get(0) , {
                api: this.fileApi() ,
                mode: 'override' ,
                multiple: false ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.src = data.data;
                } ,
                cleared () {
                    self.form.src = '';
                } ,
            });
        } ,

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search(this.users.value , (msg , data , code) => {
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

        searchVideoSubject () {
            if (this.form.module_id < 1) {
                this.error({video_subject_id: '请选择模块后操作'});
                return ;
            }
            this.pending('searchSubject' , true);
            Api.video_subject.search({
                module_id: this.form.module_id ,
                value: this.video_subjects.value ,
            } , (msg , data , code) => {
                this.pending('searchSubject' , false);
                if (code !== TopContext.code.Success) {
                    this.error({video_subject_id: data});
                    return ;
                }
                this.video_subjects.page = data.current_page;
                this.video_subjects.total = data.total;
                this.video_subjects.data = data.data;
            });
        } ,

        searchVideoSubjectEvent (e) {
            this.searchVideoSubject();
            this._val('modalForSubject' , true);
        } ,

        searchUserEvent (e) {
            // 开始搜索
            this.searchUser();
            this._val('modalForUser' , true);
        } ,

        updateSubjectEvent (row , index) {
            this.form.video_subject_id = row.id;
            this.video_subjects.current = row;
            this._val('modalForSubject' , false);
            this.video_subjects.data = [];
        } ,

        updateUserEvent (row , index) {
            this.form.user_id = row.id;
            this.users.current = row;
            this._val('modalForUser' , false);
            this.users.data = [];
        } ,

        selectedTagEvent () {

        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this.drawer = false;
            this.ins.thumb.clearAll();
            this.ins.video.clearAll();
            this.images = [];
            this.tags = [];
            // 切换回基本的内容
            this._val('tab' , 'base');
            this.users.value = '';
            this.video_subjects.value = '';
        } ,

        destroy (id , callback) {
            this.destroyAll([id] , callback);
        } ,

        destroyAll (ids , callback) {
            if (ids.length < 1) {
                this.message('warning' ,'请选择待删除的项');
                G.invoke(callback , this , false);
                return ;
            }
            const self = this;
            this.confirmModal('你确定删除吗？'  , (res) => {
                if (res) {
                    Api.video.destroyAllImageForImageSubject(ids , (msg , data , code) => {
                        G.invoke(callback , this , true);
                        this.message('success' , '操作成功' , '影响的记录数：' + data);
                        for (let i = 0; i < this.table.data.length; ++i)
                        {
                            const cur = this.table.data[i];
                            if (G.contain(cur.id , ids)) {
                                this.table.data.splice(i , 1);
                                i--;
                            }
                        }
                    });
                    return ;
                }
                G.invoke(callback , this , false);
            });
        } ,

        selectedEvent (data) {
            const ids = [];
            data.forEach((v) => {
                ids.push(v.id);
            });
            this.val.selectedIds = ids;
        } ,

        destroyEvent (index , record) {
            const pendingKey = 'delete_' + record.id;
            this.pending(pendingKey , true);
            this.destroy(record.id , () => {
                this.pending(pendingKey , false);

            });
        } ,

        destroyAllEvent () {
            this.pending('destroyAll' , true);
            this.destroyAll(this.val.selectedIds , (success) => {
                this.pending('destroyAll' , false);
                if (success) {
                    this.val.selectedIds = [];
                }
            });
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
            Api.video.destroyTag(this.form.id , tagId , (msg , data , code) => {
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
                    this.error({tags: data});
                    return ;
                }
                this.tags.push(data);
                this.dom.tagInput.html('');
            });
        } ,

        submitEvent () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            const callback = (msg , data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.successHandle((keep) => {
                    this.$emit('on-success');

                    if (!keep) {
                        this.closeFormDrawer();
                    }
                });
            };
            const form = G.copy(this.form);
            this.pending('submit' , true);
            if (this.mode === 'edit') {
                Api.video.update(form.id , form , callback);
                return ;
            }
            Api.video.store(form , callback);
        } ,

        setDatetimeEvent (date) {
            this.form.create_time = date;
        } ,

        changeEventTest (value) {
            console.log('i-select changed' , value , JSON.stringify(this.modules));
        } ,
    } ,

    watch: {
        drawer (newVal , oldVal) {
            this.$emit('change' , newVal);
        } ,

        value: {
            immediate: true ,
            handler (newVal , oldVal) {
                this.drawer = newVal;
            } ,
        } ,

        form (form , oldVal) {
            this.ins.thumb.render(form.__thumb__);
            // console.log('render' , form.__src__);
            // this.ins.video.render(form.__src__);
            this.users.current = form.user ? {...form.user} : {};
            this.video_subjects.current = form.video_subject ? {...form.video_subject} : {};
            this.videos.data = form.videos;
            this.createTime = form.create_time;
        } ,
    } ,
};
