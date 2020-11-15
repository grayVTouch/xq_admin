
const form = {
    module_id: '' ,
    weight: 0 ,
    status: 0 ,

};

const attr = [
    {
        field: '' ,
        value: '' ,
    }
];



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

export default {
    name: "index",

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                error: {} ,
                // edit-编辑 add-添加
                mode: '' ,
                selectedIds: [] ,
                // 抽屉
                drawer: false ,
                modalForUser: false ,
            } ,
            table: {
                field: [
                    {
                        type: 'selection',
                        width: TopContext.table.checkbox,
                        align: TopContext.table.alignCenter,
                        fixed: 'left' ,
                    },
                    {
                        title: 'id',
                        key: 'id',
                        minWidth: TopContext.table.id ,
                        align: TopContext.table.alignCenter,
                        fixed: 'left' ,
                    },
                    {
                        title: '名称【模块】',
                        slot: 'name',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignLeft ,
                        fixed: 'left' ,
                    },
                    {
                        title: '模块id',
                        key: 'module_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: 'thumb',
                        slot: 'thumb',
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '用户',
                        slot: 'user_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '状态',
                        slot: 'status',
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '失败原因',
                        key: 'fail_reason',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '属性',
                        slot: 'attr',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '权重',
                        key: 'weight',
                        minWidth: TopContext.table.weight ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '创建时间',
                        key: 'created_at',
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '操作',
                        slot: 'action',
                        minWidth: TopContext.table.action ,
                        align: TopContext.table.alignCenter,
                        fixed: 'right' ,
                    },
                ],
                total: 0,
                page: 1,
                data: [],
            },
            users: G.copy(users) ,
            search: {
                limit: TopContext.limit ,
                module_id: '' ,
            } ,
            form: G.copy(form)  ,
            attr ,
            modules: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
        this.getModules();
    } ,

    computed: {
        title () {
            return this.val.mode === 'edit' ? '编辑' : '添加';
        } ,

        showBatchBtn () {
            return this.val.selectedIds.length > 0;
        } ,
    } ,

    methods: {

        initDom () {
            this.dom.thumb = G(this.$refs.thumb);
        } ,

        getModules () {
            this.pending('getModules' , true);
            Api.module.all().then((res) => {
                this.pending('getModules' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.modules = data;
            });
        } ,

        initIns () {
            const self = this;
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

        getData () {
            this.pending('getData' , true);
            Api.image_subject.index(this.search , (res) => {
                this.pending('getData' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.table.total = data.total;
                this.table.page = data.current_page;
                this.handleData(data.data);
                this.table.data = data.data;
            });
        } ,

        handleData (data) {
            data.forEach((v) => {
                this.pending(`delete_${v.id}` , false);
            });
        } ,

        destroy (id , callback) {
            this.destroyAll([id] , callback);
        } ,

        destroyAll (idList , callback) {
            if (idList.length < 1) {
                this.message('warning' ,'请选择待删除的项');
                G.invoke(callback , this , false);
                return ;
            }
            const self = this;
            this.confirmModal('你确定删除吗？'  , (res) => {
                if (!res) {
                    G.invoke(callback , this , false);
                    return ;
                }
                Api.image_subject.destroyAll(idList , (res) => {
                    if (res.code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.errorHandle(res.message);
                        return ;
                    }
                    G.invoke(callback , this , true);
                    this.message('success' , '操作成功' , '影响的记录数：' + data);
                    this.getData();
                });
            });
        } ,

        selectionChangeEvent (data) {
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

        // 获取当前编辑记录详情
        findById (id) {
            return new Promise((resolve , reject) => {
                this._val('findById' , true);
                Api.image_subject.show(id , (res) => {
                    this._val('findById' , true);
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        reject();
                        return ;
                    }
                    this.form = data;
                    resolve();
                });
            });
        } ,

        editEvent (record) {
            this._val('drawer' , true);
            this._val('mode' , 'edit');
            this.error();
            this.getModules();
            this.findById(record.id).then((res) => {
                this.ins.thumb.render(this.form.thumb);

                this.attr = this.form.__attr__;
                this.users.current = this.form.user ? this.form.user : G.copy(users.current);
            });
        } ,

        addEvent () {
            this._val('drawer' , true);
            this._val('mode' , 'add');
            this.error();
            this.attr = attr;
            this.form = G.copy(form);
            this.getModules();
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (res) => {
                this.pending('submit' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.successHandle((keep) => {
                    self.getData();
                    if (keep) {
                        return ;
                    }
                    self.closeFormDrawer();
                });
            };
            this.form.attr = G.jsonEncode(this.attr);
            if (this.val.mode === 'edit') {
                Api.image_subject.update(this.form.id , this.form).then(callback);
                return ;
            }
            Api.image_subject.store(this.form).then(callback);
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning', '请求中...请耐心等待');
                return;
            }
            this.val.drawer = false;
            this.ins.thumb.clearAll();
            this.users = G.copy(users);
        },

        searchEvent () {
            this.search.page = 1;
            this.getData();
        } ,

        pageEvent (page) {
            this.search.page = page;
            this.getData();
        } ,

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search({
                value: this.users.value ,
                limit: this.users.limit ,
                page: this.users.page ,
            }, (res) => {
                this.pending('searchUser' , false);
                if (res.code !== TopContext.code.Success) {
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
        },
    } ,

}
