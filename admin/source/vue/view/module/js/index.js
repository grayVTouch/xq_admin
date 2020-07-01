
const form = {
    weight: 0 ,
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
                        width: TopContext.table.checkbox ,
                        align: TopContext.table.alignCenter ,
                    },
                    {
                        title: 'id' ,
                        key: 'id' ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '名称' ,
                        key: 'name' ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '描述' ,
                        key: 'description' ,
                        align: TopContext.table.alignLeft ,
                    } ,
                    {
                        title: '启用？' ,
                        key: 'enable' ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '权重' ,
                        key: 'weight' ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '创建时间' ,
                        key: 'create_time' ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                        align: TopContext.table.alignCenter ,
                    } ,
                ] ,
                total: 0 ,
                page: 1 ,
                data: [] ,
            } ,
            search: {
                limit: this.$store.state.context.limit
            } ,
            form: {...form}  ,
        };
    } ,

    mounted () {
        this.init();
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

        init () {
            this.initDom();
            this.initIns();
            this.getData();
        } ,


        initDom () {
        } ,



        initIns () {

        } ,

        getData () {
            this.$refs.base.show();
            this.pending('getData' , true);
            Api.module.index(this.search , (data , code) => {
                this.pending('getData' , false);
                this.$refs.base.hide();
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
                Api.module.destroyAll(idList , (data , code) => {
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
            this.form = {...record};
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.form = {...form};
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandle(data);
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