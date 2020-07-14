
const form = {
    p_id: 0 ,
    module_id: 0 ,
    enable: 1 ,
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
                        fixed: 'left' ,
                    },
                    {
                        title: 'id' ,
                        key: 'id' ,
                        width: TopContext.table.id ,
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
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '上级id' ,
                        key: 'p_id' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '描述' ,
                        key: 'description' ,
                        width: TopContext.table.desc
                    } ,
                    {
                        title: '启用?' ,
                        slot: 'enable' ,
                        width: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '权重' ,
                        key: 'weight' ,
                        width: TopContext.table.weight ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '创建时间' ,
                        key: 'create_time' ,
                        width: TopContext.table.time ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                        width: TopContext.table.action ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                ] ,
                data: [] ,
            } ,
            form: {...form}  ,
            category: [] ,
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

        showDestroyAllBtn () {
            return this.val.selectedIds.length > 0;
        } ,
    } ,

    methods: {

        getModuleData () {
            Api.module.all((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.modules = data;
            });
        } ,

        moduleChangedEvent (moduleId) {
            moduleId = parseInt(moduleId);
            this.val.error.module_id = '';
            this.form.p_id = 0;
            let category;
            if (this.val.mode === 'edit') {
                category = this.getCategoryExcludeSelfAndChildrenByIdAndModuleId(this.form.id , moduleId);
            } else {
                category = this.getCategoryByModuleId(moduleId);
            }
            this.category = category;
        } ,


        initDom () {

        } ,



        initIns () {

        } ,

        getData () {
            this.pending('getData' , true);
            Api.category.index((data , code) => {
                this.pending('getData' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
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
                Api.category.destroyAll(ids , (data , code) => {
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

            Api.category.localUpdate(record.id , {
                [extra.field]: val
            } , (data , code) => {
                this.pending(pendingKey , false);
                if (code !== TopContext.code.Success) {
                    record[extra.field] = oVal;
                    this.message('error' , data);
                    return ;
                }
                this.message('success' , '操作成功');
            });
        } ,

        selectedEvent (data) {
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

        getCategoryExcludeSelfAndChildrenByIdAndModuleId (id , moduleId) {
            const selfAndChildren = G.t.childrens(id , this.table.data , null , true , false);
            const selfAndChildrenIds = [];
            selfAndChildren.forEach((v) => {
                selfAndChildrenIds.push(v.id);
            });
            const res = [];
            this.table.data.forEach((v) => {
                if (G.contain(v.id , selfAndChildrenIds)) {
                    return ;
                }
                if (v.module_id !== moduleId) {
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

        editEvent (v) {
            this._val('modal' , true);
            this._val('mode' , 'edit');
            this.error();
            this.form = {...v};
            this.getModuleData();
            this.category = this.getCategoryExcludeSelfAndChildrenByIdAndModuleId(v.id , this.form.module_id);
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.form = {...form};
            this.getModuleData();
        } ,


        submitEvent () {
            const self = this;
            const callback = (data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandle(data);
                    return ;
                }
                this.successHandle((keep) => {
                    this.getData();
                    if (!keep) {
                        self.closeFormModal();
                    }
                });
            };
            this.pending('submit' , true);
            if (this.val.mode === 'edit') {
                Api.category.update(this.form.id , this.form ,callback);
                return ;
            }
            Api.category.store(this.form , callback);
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' ,'请求中...请耐心等待');
                return ;
            }
            this._val('modal' , false);
        } ,

    } ,
}