const form = {
    type: 'misc' ,
    user_id: '' ,
    module_id: '' ,
    category_id: '' ,
    subject_id: '' ,
    view_count: 0  ,
    praise_count: 0 ,
    weight: 0 ,
    __tag__: [] ,
    status: 0 ,
    images: [] ,
    tags: [] ,
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
            key: 'create_time' ,
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

const subjects = {
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
        {
            title: '操作' ,
            slot: 'action' ,
        } ,
    ] ,
    current: {
        id: 0 ,
        name: 'unknow' ,
    },
    total: 0 ,
    page: 1 ,
    value: '' ,
    limit: TopContext.limit ,
};

export default {
    computed: {
        title () {
            return this.val.mode === 'edit' ? '编辑' : '添加';
        } ,
    } ,

    data () {
        return {
            val: {
                tab: 'base' ,
                pending: {} ,
                error: {} ,
                selectedIds: [] ,
                modalForSubject: false ,
                modalForUser: false ,
                drawer: false ,
            } ,

            // 用户
            users: G.copy(users , true),

            // 关联主体
            subjects: G.copy(subjects , true),

            // 标签
            tags: [] ,

            // 分类
            categories: [] ,

            // 模块
            modules: [] ,

            // 前 10 标签
            topTags: [] ,

            // 元素
            dom: {} ,

            // 实例
            ins: {} ,

            table: {
                field: [
                    {
                        type: 'selection' ,
                        minWidth: TopContext.table.checkbox ,
                        center: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: 'id' ,
                        key: 'id' ,
                        center: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '图片' ,
                        slot: 'path' ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                    } ,
                ] ,
                data: [] ,
            } ,

            images: [] ,

            createTime: '' ,

            form: G.copy(form , true) ,
        };
    } ,

    props: {
        data: {
            type: Object ,
            required: true ,
        } ,
        mode: {
            type: String ,
            required: true ,
        } ,
    } ,

    mounted () {
        this.initDom();
        this.initIns();

    } ,

    methods: {

        getCategories (moduleId , callback) {
            this.pending('getCategories' , true);
            Api.category.search({
                module_id: moduleId ,
                type: 'image_subject' ,
            } , (msg , data , code) => {
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

        getModules (callback) {
            this.pending('getModules' , true);
            Api.module.all((msg , data , code) => {
                this.pending('getModules' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    G.invoke(callback , null , false);
                    return ;
                }
                this.modules = data;
                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,

        getTopTags (moduleId) {
            Api.tag.topByModuleId(moduleId , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.topTags = data;
            });
        } ,

        moduleChangedEvent (moduleId) {
            this.val.error.module_id = '';
            this.form.category_id = '';
            this.form.subject_id = '';
            this.form.subject_id = '';
            this.form.topTags = [];
            this.form.subject = G.copy(subjects.current);
            this.getCategories(moduleId);
            this.getTopTags(moduleId);
        } ,

        typeChangedEvent (type) {
            if (type === 'misc') {
                this.form.subject = G.copy(subjects.current);
                this.form.subject_id = '';
            }
        } ,

        initDom () {
            this.dom.tagInput = G(this.$refs['tag-input']);
            this.dom.tagInputOuter = G(this.$refs['tag-input-outer']);
            this.dom.thumb = G(this.$refs.thumb);
            this.dom.images = G(this.$refs.images);
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
                        console.log('图片上传失败' , data);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.thumb = data.data;
                } ,
                cleared () {
                    self.form.thumb = '';
                } ,
            });

            this.ins.images = new Uploader(this.dom.images.get(0) , {
                api: this.imageApi() ,
                mode: 'append' ,
                multiple: true ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        console.log('图片上传失败' , data);
                        return ;
                    }
                    this.status(file.id , true);
                    self.images.push(data.data);
                } ,
                cleared () {
                    self.images = [];
                    self.form.images = '';
                } ,
            });
        } ,

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search({
                value: this.users.value ,
                limit: this.users.limit ,
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

        subjectPageEvent (page) {
            this.subjects.page = page;
            this.searchSubject();
        } ,

        searchSubject () {
            this.pending('searchSubject' , true);
            Api.subject.search({
                module_id: this.form.module_id ,
                value: this.subjects.value ,
                limit: this.subjects.limit ,
            } , (msg , data , code) => {
                this.pending('searchSubject' , false);
                if (code !== TopContext.code.Success) {
                    this.error({subject_id: data});
                    return ;
                }
                this.subjects.page = data.current_page;
                this.subjects.total = data.total;
                this.subjects.data = data.data;
            });
        } ,

        searchSubjectEvent (e) {
            if (this.form.module_id < 1) {
                this.error({subject_id: '请选择模块后操作'});
                return ;
            }
            this.searchSubject();
            this._val('modalForSubject' , true);
        } ,

        searchUserEvent (e) {
            this.searchUser();
            this._val('modalForUser' , true);
        } ,

        updateSubjectEvent (row , index) {
            this.error({subject_id: ''} , false);
            this.form.subject_id = row.id;
            this.form.subject = row;
            this._val('modalForSubject' , false);
            this.subjects.data = [];
        } ,

        updateUserEvent (row , index) {
            this.error({user_id: ''} , false);
            this.form.user_id = row.id;
            this.form.user = row;
            this._val('modalForUser' , false);
            this.users.data = [];
        } ,

        selectedTagEvent () {

        } ,

        openFormDrawer () {
            this.getModules();
            if (this.mode === 'edit') {
                this.getTopTags(this.form.module_id);
                this.getCategories(this.form.module_id);
            }
            this._val('drawer' , true);
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this._val('drawer' , false);
            this.ins.thumb.clearAll();
            this.ins.images.clearAll();
            this.images = [];
            this.tags = [];
            this.topTags = [];
            this.modules = [];
            this.categories = [];
            this.users.value = '';
            this.subjects.value = '';
            this._val('tab' , 'base');
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
                    Api.image_subject.destroyAllImageForImageSubject(ids , (msg , data , code) => {
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
            Api.image_subject.destroyTag({
                image_subject_id: this.form.id ,
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
            this.pending('submit' , true);
            if (this.mode === 'edit') {
                Api.image_subject.update(form.id , form , callback);
                return ;
            }
            Api.image_subject.store(form , callback);
        } ,

        setCreateTimeEvent (date) {
            this.form.create_time = date;
        } ,

    } ,

    watch: {
        data (data) {
            if (G.isEmptyObject(data)) {
                this.form = G.copy(form , true);
            } else {
                this.form = data;
            }
            this.ins.thumb.render(this.form.thumb);
        } ,
        form: {
            deep: true ,
            handler (form) {
                this.table.data = form.images;
                this.users.current = form.user ? form.user : G.copy(users.current);
                this.subjects.current = form.subject ? form.subject: G.copy(subjects.current);
                this.createTime = form.create_time;
            } ,
        } ,
    } ,
};

