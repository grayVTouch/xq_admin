
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
                        title: '权重' ,
                        key: 'weight' ,
                        minWidth: TopContext.table.weight ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '创建时间' ,
                        key: 'created_at' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                        minWidth: TopContext.table.action + 100 ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
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
                limit: TopContext.limit
            } ,
            form: G.copy(form)  ,
            role: {} ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getPermission();
        this.getData();
    } ,

    computed: {
        title () {
            return this.val.mode === 'edit' ? '编辑' : '添加';
        } ,

        showBatchBtn () {
            return this.selection.length > 0;
        } ,
    } ,

    methods: {
        initDom () {

        } ,

        initIns () {

        } ,

        getPermission () {
            Api.admin_permission.index().then((res) => {
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
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
            Api.role.index(this.search , (res) => {
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
                Api.role.destroyAll(idList , (res) => {
                    if (res.code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.errorHandle(res.message);
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
            } , (res) => {
                this.pending(pendingKey , false);
                if (res.code !== TopContext.code.Success) {
                    record[extra.field] = oVal;
                    this.notice(data);
                    return ;
                }
                this.notice('操作成功');
            });
        } ,

        selectionChangeEvent (data) {
            const ids = [];
            data.forEach((v) => {
                ids.push(v.id);
            });
            this.selection = ids;
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
            const ids = this.selection.map((v) => {
                return v.id;
            });
            this.destroyAll(ids , (success) => {
                this.pending('destroyAll' , false);
                if (success) {
                    this.selection = [];
                }
            });
        } ,

        editEvent (record) {
            this._val('modal' , true);
            this._val('mode' , 'edit');
            this.error();
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
            this.pending('submitEvent' , true);
            const callback = (res) => {
                this.pending('submitEvent' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
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
                Api.role.update(this.form.id , this.form).then(callback);
                return ;
            }
            Api.role.store(this.form).then(callback);
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
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
            this.role = G.copy(record);
            this._val('drawer' , true);
            Api.role.permission(record.id , (res) => {
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
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

        closeFormModal() {
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
            } , (res) => {
                this.pending('allocatePermission' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.successHandle((keep) => {
                    this.getData();
                    if (keep) {
                        return ;
                    }
                    this.closeFormModal();
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
