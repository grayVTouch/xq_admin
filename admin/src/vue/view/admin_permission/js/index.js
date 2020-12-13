import Form from '../form.vue';



export default {
    name: "index",

    components: {
        'my-form': Form
    } ,

    data () {
        const field = [
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
                title: '中文名称' ,
                slot: 'cn' ,
                minWidth: TopContext.table.name ,
                fixed: 'left' ,
            } ,
            {
                title: '英文名称' ,
                key: 'en' ,
                minWidth: TopContext.table.name ,
                align: TopContext.table.alignCenter ,
            } ,
            {
                title: '小图标' ,
                minWidth: TopContext.table.image ,
                slot: 's_ico' ,
                align: 'center'
            } ,
            {
                title: '大图标' ,
                minWidth: TopContext.table.image ,
                slot: 'b_ico' ,
                align: 'center'
            } ,
            {
                title: '上级id' ,
                key: 'p_id' ,
                minWidth: TopContext.table.id ,
                align: TopContext.table.alignCenter ,
            } ,
            {
                title: '权限值' ,
                key: 'value' ,
                minWidth: TopContext.table.w_200 ,
                align: TopContext.table.alignLeft ,
            } ,
            {
                title: '描述' ,
                key: 'description' ,
                minWidth: TopContext.table.desc ,
            } ,
            {
                title: '类型' ,
                key: 'type' ,
                minWidth: TopContext.table.type ,
                align: TopContext.table.alignCenter ,
            } ,
            {
                title: '菜单?' ,
                slot: 'is_menu' ,
                minWidth: TopContext.table.status ,
                align: TopContext.table.alignCenter ,
                fixed: 'right' ,
            } ,
            {
                title: '视图?' ,
                slot: 'is_view' ,
                minWidth: TopContext.table.status ,
                align: TopContext.table.alignCenter ,
                fixed: 'right' ,
            } ,
            {
                title: '启用?' ,
                slot: 'enable' ,
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
        ];
        if (!this.TopContext.debug) {
            // 仅在调试模式可供修改
            field.shift();
            field.pop();
        }
        return {
            filter: {
                id: '' ,
            } ,
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                drawer: false ,
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
                field: field ,
                data: [] ,
            } ,
            form: {}  ,
            permission: [] ,
        };
    } ,

    mounted () {
        this.init();
    } ,

    computed: {
        showBatchBtn () {
            return this.selection.length > 0;
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
            this.pending('getData' , true);
            Api.admin_permission.index().then((res) => {
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
                this.pending(`is_menu_${v.id}` , false);
                this.pending(`is_view_${v.id}` , false);
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
                Api.admin_permission.destroyAll(ids , (res) => {
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

            Api.admin_permission.localUpdate(record.id , {
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

        editEvent (v) {
            this._val('mode' , 'edit');
            this.form = G.copy(v);
            this.$nextTick(() => {
                this.$refs.form.openFormModal();
            });
        } ,

        addEvent () {
            this._val('mode' , 'add');
            this.form = {};
            this.$nextTick(() => {
                this.$refs.form.openFormModal();
            });
        } ,


    } ,
}
