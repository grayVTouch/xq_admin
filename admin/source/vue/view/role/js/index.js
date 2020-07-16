
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
                attr: {
                    id: 'id',
                    p_id: 'p_id',
                } ,
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
            // 角色权限
            permission: [] ,
            // 所有权限
            permissions: [] ,
            // 选中的权限
            rolePermission: [] ,
            search: {
                limit: this.$store.state.context.limit
            } ,
            form: {...form}  ,
            role: {} ,
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
            this.getPermission();
            this.getData();
        } ,


        initDom () {
        } ,



        initIns () {

        } ,

        getPermission () {
            Api.admin_permission.index((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                data.forEach((v) => {
                    // 展开
                    v.expand = true;
                    v.title = v.cn;
                });
                this.permissions = data;
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.role.index(this.search , (msg , data , code) => {
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
            this.confirmModal('你确定删除吗？'  , (res) => {
                if (!res) {
                    G.invoke(callback , this , false);
                    return ;
                }
                Api.role.destroyAll(idList , (msg , data , code) => {
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

        updateBoolValEvent (val , extra) {
            const oVal = val > 0 ? 0 : 1;
            const pendingKey = `${extra.field}_${extra.id}`;
            const record = this.findRecordById(extra.id);
            this.pending(pendingKey , true);

            Api.role.localUpdate(record.id , {
                [extra.field]: val
            } , (msg , data , code) => {
                this.pending(pendingKey , false);
                if (code !== TopContext.code.Success) {
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

        allocateEvent (record) {
            this.role = {...record};
            this._val('drawer' , true);
            Api.role.permission(record.id , (msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                const permissions = [...this.permissions];
                permissions.forEach((v) => {
                    for (let i = 0; i < data.length; ++i)
                    {
                        const cur = data[i];
                        if (cur.id === v.id) {
                            v.checked = true;
                            v.selected = true;
                            return ;
                        }
                    }
                    v.checked = false;
                    v.selected = false;
                });
                const permission = G.t.childrens(0 , permissions , this.val.attr , false , true);
                this.permission = permission;
                this.rolePermission = data;
            });
        } ,

        closeFormDrawer() {
            if (this.pending('allocatePermission')) {
                this.message('请求中...请耐心等待');
                return ;
            }
            this._val('drawer' , false);
            this.rolePermission = [];
        } ,

        allocatePermission () {
            this.pending('allocatePermission' , true);
            const ids = [];
            this.rolePermission.forEach((v) => {
                ids.push(v.id);
            });
            Api.role.allocatePermission(this.role.id , {
                permission: G.jsonEncode(ids)
            } , (msg , data , code) => {
                this.pending('allocatePermission' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.successHandle((keep) => {
                    this.getData();
                    if (keep) {
                        return ;
                    }
                    this.closeFormDrawer();
                });
            });
        } ,

        permissionCheckedEvent (data ,cur) {
            cur.selected = cur.checked;
            this.rolePermission = data;
        } ,

        permissionSelectedEvent (data , cur) {
            cur.checked = cur.selected;
            this.rolePermission = data;
        } ,
    } ,
}