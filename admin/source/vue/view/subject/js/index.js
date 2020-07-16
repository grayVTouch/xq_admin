
const form = {
    module_id: 0 ,
    weight: 0 ,
};

const attr = [
    {
        field: '' ,
        value: '' ,
    }
];

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

            } ,
            table: {
                field: [
                    {
                        type: 'selection',
                        width: TopContext.table.checkbox,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: 'id',
                        key: 'id',
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '名称【模块】',
                        slot: 'name',
                        align: TopContext.table.alignLeft
                    },
                    {
                        title: '模块id',
                        key: 'module_id',
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: 'thumb',
                        slot: 'thumb',
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '属性',
                        slot: 'attr',
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '权重',
                        key: 'weight',
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '创建时间',
                        key: 'create_time',
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '操作',
                        slot: 'action',
                        align: TopContext.table.alignCenter,
                    },
                ],
                total: 0,
                page: 1,
                data: [],
            },
            search: {
                limit: this.$store.state.context.limit ,
            } ,
            form: {...form}  ,
            attr ,
            modules: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
    } ,

    computed: {
        title () {
            return this.val.mode === 'edit' ? '编辑' : '添加';
        } ,

        showDestroyAllBtn () {
            return this.val.selectedIds.length > 0;
        } ,
    } ,

    methods: {

        initDom () {
            this.dom.thumb = G(this.$refs.thumb);
        } ,

        getModuleData () {
            Api.module.all((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.modules = data;
            });
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
        } ,

        getData () {
            this.pending('getData' , true);
            Api.subject.index(this.search , (msg , data , code) => {
                this.pending('getData' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
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
                Api.subject.destroyAll(idList , (msg , data , code) => {
                    if (code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.message('error' , data);
                        return ;
                    }
                    G.invoke(callback , this , true);
                    this.message('success' , '操作成功' , '影响的记录数：' + data);
                    this.getData();
                });
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

        editEvent (record) {
            this._val('drawer' , true);
            this._val('mode' , 'edit');
            this.error();
            this.attr = record.__attr__;
            this.form = {...record};
            this.ins.thumb.render(record.__thumb__);
            this.getModuleData();
        } ,

        addEvent () {
            this._val('drawer' , true);
            this._val('mode' , 'add');
            this.error();
            this.attr = attr;
            this.form = {...form};
            this.getModuleData();
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
                    self.getData();
                    if (keep) {
                        return ;
                    }
                    self.closeFormDrawer();
                });
            };
            this.form.attr = G.jsonEncode(this.attr);
            if (this.val.mode === 'edit') {
                Api.subject.update(this.form.id , this.form , callback);
                return ;
            }
            Api.subject.store(this.form , callback);
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.val.drawer = false;
            this.ins.thumb.clearAll();

        } ,

        searchEvent () {
            this.search.page = 1;
            this.getData();
        } ,

        pageEvent (page) {
            this.search.page = page;
            this.getData();
        } ,
    } ,
}