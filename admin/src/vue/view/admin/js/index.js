import MyForm from '../form.vue';

const search = {
    role_id: '' ,
    limit: TopContext.limit ,
    order: '' ,
};

const current = {id: 0};

export default {
    name: "index",

    components: {
        'my-form': MyForm ,
    } ,

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                // edit-编辑 add-添加
                mode: '' ,
                selectedIds: [] ,
                birthday: '' ,
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
                        sortable: 'custom' ,
                    } ,
                    {
                        title: '用户名' ,
                        key: 'username' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '角色【id】' ,
                        slot: 'role_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '头像' ,
                        slot: 'avatar' ,
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '性别' ,
                        key: '__sex__' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '生日' ,
                        key: 'birthday' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        sortable: 'custom' ,
                    } ,
                    {
                        title: '手机号码' ,
                        key: 'phone' ,
                        minWidth: TopContext.table.phone ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '电子邮箱' ,
                        key: 'email' ,
                        minWidth: TopContext.table.email ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '最近一次登录时间' ,
                        key: 'last_time' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter ,
                        sortable: 'custom' ,
                    } ,
                    {
                        title: '最近一次登录ip' ,
                        key: 'last_ip' ,
                        minWidth: TopContext.table.ip ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '超级管理员' ,
                        slot: 'is_root' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '创建时间' ,
                        key: 'created_at' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter ,
                        sortable: 'custom' ,
                    } ,
                    // {
                    //     title: '操作' ,
                    //     slot: 'action' ,
                    //     minWidth: TopContext.table.action ,
                    //     align: TopContext.table.alignCenter ,
                    //     fixed: 'right' ,
                    // } ,
                ] ,
                total: 0 ,
                page: 1 ,
                data: [] ,
            } ,

            // 角色列表
            roles: [] ,

            search: G.copy(search) ,

            // 当前编辑的用户
            current: G.copy(current) ,

            // 选中项
            selection: [] ,
        };
    } ,

    mounted () {
        // this.initDom();
        // this.initIns();
        this.getRoles();
        this.getData();
    } ,

    computed: {

        showBatchBtn () {
            return this.val.selectedIds.length > 0;
        } ,
    } ,

    methods: {



        getRoles () {
            this.pending('getRoles' , true);
            Api.role.all((msg , data , code) => {
                this.pending('getRoles' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.roles = data;
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.admin.index(this.search , (msg , data , code) => {
                this.pending('getData' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
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
                // 重置密码
                v.password = '';
                this.pending(`delete_${v.id}` , false);
            });
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
            this.confirmModal('你确定删除吗？'  , (res) => {
                if (!res) {
                    G.invoke(callback , this , false);
                    return ;
                }
                Api.admin.destroyAll(ids , (msg , data , code) => {
                    if (code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.message('error' , msg);
                        return ;
                    }
                    G.invoke(callback , this , true);
                    this.message('success' , '操作成功，影响的记录数：' + data);
                    this.getData();
                });
            });
        } ,

        selectionChangeEvent (selection) {
            this.selection = selection;
        } ,

        getIdsFromSelection () {
            return this.selection.map((v) => {
                v.id;
            });
        } ,

        rowClickEvent (row , index) {
            this.$refs.table.toggleSelect(index);
        } ,

        rowDblclickEvent (row , index) {
            this.editEvent(row);
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
            this.destroyAll(this.getIdsFromSelection() , (success) => {
                this.pending('destroyAll' , false);
                if (success) {
                    this.val.selectedIds = [];
                }
            });
        } ,

        isOnlyOneSelection () {
            return this.selection.length === 1;
        } ,

        isEmptySelection () {
            return this.selection.length === 0;
        } ,

        hasSelection () {
            return this.selection.length > 0;
        } ,

        getFirstSelection () {
            return this.selection[0];
        } ,

        checkOneSelection () {
            if (!this.hasSelection()) {
                this.errorHandle('请选择项');
                return false;
            }
            if (!this.isOnlyOneSelection()) {
                this.errorHandle('请仅选择一项');
                return false;
            }
            return true;
        } ,

        edit (record) {
            this.current = record;
            this._val('mode' , 'edit');
            this.$nextTick(() => {
                this.$refs.form.openFormModal();
            });
        } ,

        editEvent (record) {
            this.edit(record);
        } ,

        editEventByButton () {
            if (!this.checkOneSelection()) {
                return ;
            }
            const current = this.getFirstSelection();
            this.edit(current);
        } ,

        addEvent () {
            this._val('mode' , 'add');
            this.$nextTick(() => {
                this.$refs.form.openFormModal();
            });
        } ,

        searchEvent () {
            this.search.page = 1;
            this.getData();
        } ,

        pageEvent (page) {
            this.search.page = page;
            this.getData();
        } ,

        sortChangeEvent (data) {
            if (data.order === TopContext.sort.none) {
                this.search.order = '';
            } else {
                this.search.order = this.generateOrderString(data.key , data.order);
            }
            this.table.page = 1;
            this.getData();
        } ,
    } ,

    watch: {
        form (form , oldVal) {
            this.val.birthday = form.birthday;
            this.ins.avatar.render(form.avatar);
        } ,
    } ,
}
