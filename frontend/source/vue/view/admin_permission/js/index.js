import Form from '../form.vue';

const form = {
    type: 'view' ,
    p_id: 0 ,
    enable: 1 ,
    is_menu: 0 ,
    is_view: 1 ,
    weight: 0 ,
    s_ico: '' ,
    b_ico: '' ,
};

export default {
    name: "index",

    components: {
        'my-form': Form
    } ,

    data () {
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
                        title: '中文名称' ,
                        slot: 'cn' ,
                        width: TopContext.table.name ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '英文名称' ,
                        key: 'en' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '小图标' ,
                        width: TopContext.table.image ,
                        slot: 's_ico' ,
                        align: 'center'
                    } ,
                    {
                        title: '大图标' ,
                        width: TopContext.table.image ,
                        slot: 'b_ico' ,
                        align: 'center'
                    } ,
                    {
                        title: '上级id' ,
                        key: 'p_id' ,
                        width: TopContext.table.id ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '权限值' ,
                        key: 'value' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '描述' ,
                        key: 'description' ,
                        width: TopContext.table.desc ,
                    } ,
                    {
                        title: '类型' ,
                        key: 'type' ,
                        width: TopContext.table.type ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '菜单?' ,
                        slot: 'is_menu' ,
                        width: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '视图?' ,
                        slot: 'is_view' ,
                        width: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
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
                        title: '创建时间?' ,
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
        };
    } ,

    mounted () {
        this.init();
    } ,

    computed: {
        drawerTitle () {
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
            Api.adminPermission.index((data , code) => {
                this.$refs.base.hide();
                if (code !== TopContext.successCode) {
                    this.message('error' , data);
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
                    Api.adminPermission.destroyAll(idList , (data , code) => {
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

            Api.adminPermission.localUpdate(record.id , {
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
            this.val.drawer = true;
            this.val.mode = 'edit';
            this.form = {...v};
        } ,

        addEvent () {
            this.val.drawer = true;
            this.val.mode = 'add';
            this.error();
            this.form = {...form};
        } ,


    } ,
}