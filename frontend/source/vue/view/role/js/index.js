
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
                model: false ,
                attr: {
                    id: 'id',
                    floor: 'floor',
                    name: 'cn'
                } ,
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
                        title: '权重' ,
                        key: 'weight' ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '创建时间?' ,
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
            search: {} ,
            form: {...form}  ,
        };
    } ,

    mounted () {
        this.init();
    } ,

    computed: {
        modalTitle () {
            return this.val.mode === 'edit' ? '编辑权限' : '添加权限';
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
            Api.role.index(this.search , (data , code) => {
                this.pending('getData' , false);
                this.$refs.base.hide();
                if (code !== TopContext.successCode) {
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
                this.pending(`is_menu_${v.id}` , false);
                this.pending(`is_view_${v.id}` , false);
                this.pending(`enable_${v.id}` , false);
                this.pending(`delete_${v.id}` , false);
                this.select(`select_${v.id}` , false);
            });
        } ,

        findRecordById (id) {
            for (let i = 0; i < this.table.data.length; ++i)
            {
                const cur = this.table.data[i];
                if (cur.id == id) {
                    return cur;
                }
            }
            throw new Error('未找到 id 对应记录：' + id);
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
            this.modal('confirm' , '你确定删除吗？' , '' , null , {
                onOk () {
                    Api.role.destroyAll(idList , (data , code) => {
                        G.invoke(callback , self , true);
                        self.message('success' , '操作成功' , '影响的记录数：' + data);
                        self.getData();
                    });
                } ,
                onCancel () {
                    G.invoke(callback , self , false);
                } ,
            });
        } ,

        updateBoolValEvent (val , extra) {
            const oVal = val > 0 ? 0 : 1;
            const pendingKey = `${extra.field}_${extra.id}`;
            const record = this.findRecordById(extra.id);
            this.pending(pendingKey , true);

            Api.role.localUpdate(record.id , {
                [extra.field]: val
            } , (data , code) => {
                this.pending(pendingKey , false);
                if (code !== TopContext.successCode) {
                    record[extra.field] = oVal;
                    this.notice(data);
                    return ;
                }
                this.notice('操作成功');
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

        editEvent (v) {
            this.val.modal = true;
            this.val.mode = 'edit';
            this.form = {...v};
        } ,

        addEvent () {
            this.val.modal = true;
            this.val.mode = 'add';
            this.error();
            this.form = {...form};
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (data , code) => {
                this.pending('submit' , false);
                if (code !== TopContext.successCode) {
                    if (G.isString(data)) {
                        this.message('error' , data);
                        return ;
                    }
                    this.message('error' , '操作失败，请检查表单');
                    this.error(data);
                    return ;
                }
                this.modal('confirm' , '操作成功，继续操作？' , '' , null , {
                    onOk () {
                        self.getData();
                    } ,

                    onCancel () {
                        self.getData();
                        self.closeFormModal();
                    } ,
                });
            };
            if (this.val.mode === 'edit') {
                Api.role.update(this.form.id , this.form , callback);
                return ;
            }
            Api.role.store(this.form , callback);
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