
const form = {
    module_id: '' ,
    position_id: '' ,
};

export default {
    name: "index",

    data () {
        return {
            filter: {
                id: '' ,
            } ,
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                modal: false ,
                error: {} ,
                // edit-编辑 add-添加
                mode: '' ,
                selectedIds: [] ,

            } ,

            // 表单中的模块
            modules: [] ,

            // 这个属于保护字段！！不允许使用
            positions: [] ,

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
                        title: '位置' ,
                        slot: 'position_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '图片' ,
                        slot: 'path' ,
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '模块【id】' ,
                        slot: 'module_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,

                    // {
                    //     title: '文件名' ,
                    //     key: 'name' ,
                    //     align: TopContext.table.alignCenter ,
                    // } ,
                    // {
                    //     title: 'mime' ,
                    //     key: 'mime' ,
                    //     align: TopContext.table.alignCenter ,
                    // } ,
                    {
                        title: '链接' ,
                        key: 'link' ,
                        minWidth: TopContext.table.link ,
                        align: TopContext.table.alignCenter ,
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
                limit: TopContext.limit ,
            } ,

            search: {
                limit: TopContext.limit ,
                module_id: '' ,
                position_id: '' ,
            } ,
            form: G.copy(form)  ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
        this.getModules();
        this.getPositions();
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
            this.dom.path = G(this.$refs.path);
        } ,

        getPositions () {
            this.pending('getPositions' , true);
            Api.position.all((msg , data , code) => {
                this.pending('getPositions' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.positions = data;
            });
        } ,

        getModules () {
            this.pending('getModules' , true);
            Api.module.all((msg , data , code) => {
                this.pending('getModules' , false);
                if (code !== TopContext.code.Success) {
                    this.error('error' , data);
                    return ;
                }
                this.modules = data;
            });
        } ,


        initIns () {
            const self = this;
            this.ins.path = new Uploader(this.dom.path.get(0) , {
                api: this.imageApi(true) ,
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.path = data.data;
                } ,
                cleared () {
                    self.form.path = '';
                } ,
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.image_at_position.index(this.search , (msg , data , code) => {
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
                Api.image_at_position.destroyAll(idList , (msg , data , code) => {
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
            this._val('modal' , true);
            this._val('mode' , 'edit');
            this.error();
            this.form = G.copy(record);
            this.getPositions();
            this.getModules();
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.form = G.copy(form);
            this.getPositions();
            this.getModules();
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
                    self.closeFormModal();
                });
            };
            if (this.val.mode === 'edit') {
                Api.image_at_position.update(this.form.id , this.form , callback);
                return ;
            }
            Api.image_at_position.store(this.form , callback);
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.val.modal = false;
            this.ins.path.clearAll();
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

    watch: {
        form (form) {
            this.ins.path.render(form.__path__);
        } ,
    } ,
}