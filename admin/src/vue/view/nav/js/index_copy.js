
const form = {
    p_id: 0 ,
    module_id: '' ,
    enable: 1 ,
    is_menu: 0 ,
    weight: 0 ,
    platform: '' ,
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
                        title: '名称【模块】' ,
                        slot: 'name' ,
                        width: 600 ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '模块id',
                        key: 'module_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '上级id' ,
                        key: 'p_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: 'value' ,
                        key: 'value' ,
                        minWidth: TopContext.table.link ,
                        align: TopContext.table.alignLeft ,
                    } ,
                    {
                        title: '所属平台' ,
                        key: '__platform__' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '菜单?' ,
                        slot: 'is_menu' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '启用?' ,
                        slot: 'enable' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
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
                        minWidth: TopContext.table.action ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                ] ,
                data: [] ,
            } ,
            form: G.copy(form)  ,

            navs: [] ,

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
            return this.val.mode === 'edit' ? '编辑分类' : '添加分类';
        } ,

        showBatchBtn () {
            return this.selection.length > 0;
        } ,
    } ,

    methods: {

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

        getNavs () {
            this.pending('getNavs' , true);
            Api.nav.getByModuleId(this.form.module_id ? this.form.module_id : 0 , (res) => {
                this.pending('getNavs' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.navs = this.getNavsExcludeSelfAndChildrenById(this.form.id , data);
                console.log('过滤后数据' , G.jsonEncode(this.navs));
            });
        } ,

        moduleChangedEvent (moduleId) {
            this.val.error.module_id = '';
            this.form.p_id = 0;
            this.getNavs();
        } ,


        initDom () {

        } ,



        initIns () {

        } ,

        getData () {
            this.pending('getData' , true);
            Api.nav.index().then((res) => {
                this.pending('getData' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.handleData(data);
                this.table.data = data;
            });
        } ,

        handleData (data) {
            data.forEach((v) => {
                this.pending(`enable_${v.id}` , false);
                this.pending(`delete_${v.id}` , false);
                this.pending(`is_menu_${v.id}` , false);
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

        destroyAll (ids , callback) {
            if (ids.length < 1) {
                this.message('warning' ,'请选择待删除的项');
                G.invoke(callback , this , false);
                return ;
            }
            const self = this;
            this.confirmModal('将会连同子类一并删除，确定操作吗？' , (res) => {
                if (!res) {
                    G.invoke(callback , self , false);
                    return ;
                }
                Api.nav.destroyAll(ids , (res) => {
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

            Api.nav.localUpdate(record.id , {
                [extra.field]: val
            } , (res) => {
                this.pending(pendingKey , false);
                if (res.code !== TopContext.code.Success) {
                    record[extra.field] = oVal;
                    this.errorHandle(res.message);
                    return ;
                }
                this.message('success' , '操作成功');
            });
        } ,

        selectionChangeEvent (data) {
            const ids = [];
            data.forEach((v) => {
                ids.push(v.id);
            });
            this._val('selectedIds' , ids);
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
            this.destroyAll(this._val('selectedIds') , (success) => {
                this.pending('destroyAll' , false);
                if (success) {
                    this._val('selectedIds' , []);
                }
            });
        } ,

        getNavsExcludeSelfAndChildrenById (id , data) {
            const selfAndChildren = G.t.childrens(id , data , null , true , false);
            const selfAndChildrenIds = [];
            selfAndChildren.forEach((v) => {
                selfAndChildrenIds.push(v.id);
            });
            const res = [];
            data.forEach((v) => {
                if (G.contain(v.id , selfAndChildrenIds)) {
                    return ;
                }
                res.push(v);
            });
            return res;
        } ,

        getCategoryByModuleId (moduleId) {
            const res = [];
            this.table.data.forEach((v) => {
                if (v.module_id !== moduleId) {
                    return ;
                }
                res.push(v);
            });
            return res;
        } ,

        editEvent (record) {
            this._val('modal' , true);
            this._val('mode' , 'edit');
            this.error();
            this.form = G.copy(record);
            this.getModules();
            this.getNavs();
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.form = G.copy(form);
            this.getModules();
            this.getNavs();
        } ,


        submitEvent () {
            const self = this;
            const callback = (res) => {
                this.pending('submitEvent' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.successHandle((keep) => {
                    this.getData();
                    if (!keep) {
                        self.closeFormModal();
                    }
                });
            };
            this.pending('submitEvent' , true);
            if (this.val.mode === 'edit') {
                Api.nav.update(this.form.id , this.form ,callback);
                return ;
            }
            Api.nav.store(this.form).then(callback);
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
                this.message('warning' ,'请求中...请耐心等待');
                return ;
            }
            this._val('modal' , false);
        } ,

    } ,
}
