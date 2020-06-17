export default {
    data () {
        return {
            val: {
                tab: 'base' ,
                pending: {} ,
                error: {} ,
                // 用户添加的变迁
                tags: [] ,
                selectedIds: [] ,
            } ,

            drawer: false ,

            // 用户
            users: [] ,

            // 关联主体
            subjects: [] ,

            // 标签
            tags: [] ,

            // 标签
            dom: {} ,

            // 实例
            ins: {} ,

            table: {
                field: [
                    {
                        type: 'selection' ,
                        width: TopContext.table.checkbox ,
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
            // 用户追加的标签

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
            default: {} ,
        } ,
        categories: {
            type: Array ,
            default: [] ,
        } ,
        modules: {
            type: Array ,
            default: [] ,
        } ,
        topTags: {
            type: Array ,
            default: [] ,
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

        initDom () {
            this.dom.tagInput = G(this.$refs['tag-input']);
            this.dom.tagInputOuter = G(this.$refs['tag-input-outer']);
            this.dom.thumb = G(this.$refs.thumb);
            this.dom.images = G(this.$refs.images);
        } ,

        initIns () {
            const self = this;
            this.ins.thumb = new Uploader(this.dom.thumb.get(0) , {
                api: TopContext.fileApi ,
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.thumb = data;
                } ,
                cleared () {
                    self.form.thumb = '';
                } ,
            });

            this.ins.images = new Uploader(this.dom.images.get(0) , {
                api: TopContext.fileApi ,
                mode: 'append' ,
                multiple: true ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.images.push(data);
                } ,
                cleared () {
                    self.images = [];
                    self.form.images = '';
                } ,
            });
        } ,

        searchUser (value) {
            Api.user.search(value , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.error({user_id: data});
                    return ;
                }
                this.users = data;
            });
        } ,

        searchSubject (value) {
            Api.subject.search(value , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.error({subject_id: data});
                    return ;
                }
                this.subjects = data;
            });
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
            this.ins.images.clearAll();
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
                    Api.image_subject.destroyAllImageForImageSubject(ids , (data , code) => {
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

        isExistTag (name) {
            for (let i = 0; i < this.form.__tag__.length; ++i)
            {
                const cur = this.form.__tag__[i];
                if (name === cur.name) {
                    return true;
                }
            }
            return false;
        } ,

        appendTag (name) {
            if (this.isExistTag(name)) {
                this.message('error' , '标签已经存在');
                return ;
            }
            this.form.__tag__.push({name});
        } ,

        destroyTag (name) {
            for (let i = 0; i < this.form.__tag__.length; ++i)
            {
                const cur = this.form.__tag__[i];
                if (name === cur.name) {
                    this.form.__tag__.splice(i , 1);
                    return ;
                }
            }
        } ,

        createOrAppendTag () {
            const name = this.dom.tagInput.text().replace(/\s/g , '');
            this.dom.tagInput.html(name);
            if (!G.isValid(name)) {
                this.message('error' , '请提供标签名称');
                return ;
            }
            if (this.isExistTag(name)) {
                this.message('error' , '标签已经存在');
                return ;
            }
            this.dom.tagInput.origin('blur');
            this.dom.tagInputOuter.addClass('disabled');
            Api.tag.store({
                name ,
            } , (data , code) => {
                this.form.__tag__.push({name});
                this.dom.tagInputOuter.removeClass('disabled');
                this.dom.tagInput.html('');
            });
        } ,

        submitEvent () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            const callback = (data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandle(data);
                    return ;
                }
                this.successHandle((keep) => {
                    this.$emit('on-success');
                    if (!keep) {
                        this.closeFormDrawer();
                    }
                });
            };
            this.form.images = G.jsonEncode(this.images);
            this.form.tag = G.jsonEncode(this.form.__tag__);
            this.pending('submit' , true);
            if (this.mode === 'edit') {
                Api.image_subject.update(this.form.id , this.form , callback);
                return ;
            }
            Api.image_subject.store(this.form , callback);
        } ,

        setDatetimeEvent (date) {
            this.form.create_time = date;
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
            this.table.data = form.images;
            this.ins.thumb.render(form.thumb);
            this.createTime = form.create_time;
        } ,
    } ,
};

