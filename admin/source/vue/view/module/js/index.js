
const form = {
    weight: 0 ,
    auth: 0 ,
    enable: 1 ,
    default: 0 ,
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
                        key: 'name' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '描述' ,
                        key: 'description' ,
                        minWidth: TopContext.table.desc ,
                        align: TopContext.table.alignLeft ,
                    } ,
                    {
                        title: '启用？' ,
                        slot: 'enable' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                    {
                        title: '默认？' ,
                        slot: 'default' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                    {
                        title: '认证？' ,
                        slot: 'auth' ,
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
            search: {
                limit: TopContext.limit
            } ,
            form: G.copy(form)  ,
        };
    } ,

    mounted () {
        this.init();
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

        init () {
            this.initDom();
            this.initIns();
            this.getData();
        } ,


        initDom () {
        } ,



        initIns () {

        } ,

        updateBoolValEvent (val , extra) {
            const oVal = val > 0 ? 0 : 1;
            const pendingKey = `${extra.field}_${extra.data.id}`;
            this.pending(pendingKey , true);
            Api.module.localUpdate(extra.data.id , {
                [extra.field]: val
            } , (msg , data , code) => {
                this.pending(pendingKey , false);
                if (code !== TopContext.code.Success) {
                    extra.data[extra.field] = oVal;
                    this.message('error' , data);
                    return ;
                }
                this.message('success' , '操作成功');
                if (extra.field === 'default') {
                    this.getData();
                }
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.module.index(this.search , (msg , data , code) => {
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
                Api.module.destroyAll(idList , (msg , data , code) => {
                    if (code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.message('error' , data);
                        return ;
                    }
                    G.invoke(callback , self , true);
                    self.message('success' , '操作成功' , '影响的记录数：' + data);
                    self.getData();
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
            record.auth_password = '';
            this.form = G.copy(record);
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.form = G.copy(form);
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
                Api.module.update(this.form.id , this.form , callback);
                return ;
            }
            Api.module.store(this.form , callback);
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.val.modal = false;

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