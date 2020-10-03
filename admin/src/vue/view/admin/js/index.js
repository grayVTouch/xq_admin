
const form = {
    sex: 'secret' ,
    role_id: '' ,
};

const search = {
    role_id: '' ,
    limit: TopContext.limit ,
    order: '' ,
};

export default {
    name: "index",

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                modal: false ,
                error: {} ,
                // edit-编辑 add-添加
                mode: '' ,
                selectedIds: [] ,
                birthday: '' ,
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
                        align: TopContext.table.alignCenter
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
                        align: TopContext.table.alignCenter
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

            // 角色列表
            roles: [] ,

            search: G.copy(search) ,
            form: G.copy(form)  ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getRoles();
        this.getData();
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

        setDate (date) {
            this.form.birthday = date;
        } ,

        getRoles () {
            this.pending('getRoles' , true);
            Api.role.all((msg , data , code) => {
                this.pending('getRoles' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.roles = data;
            });
        } ,

        addRoleEvent () {
            const route = '/role/index';
            this.location(route , {
                action: 'add' ,
                callback: () => {
                    // 更新角色
                    this.getRoles();
                    // 切换回当前的标签
                    this.location(this.route.value , null , '_blank');
                    // 关闭标签
                    this.closeTabByRoute(route);
                } ,
            } , '_blank');
        } ,

        initDom () {
            this.dom.avatar = G(this.$refs.avatar);
        } ,

        initIns () {
            const self = this;
            this.ins.avatar = new Uploader(this.dom.avatar.get(0) , {
                api: this.thumbApi() ,
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.avatar = data.data;
                } ,
                cleared () {
                    self.form.avatar = '';
                } ,
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.admin.index(this.search , (msg , data , code) => {
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
                // 重置密码
                v.password = '';
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
                Api.admin.destroyAll(idList , (msg , data , code) => {
                    if (code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.message('error' , data);
                        return ;
                    }
                    G.invoke(callback , this , true);
                    this.message('success' , '操作成功，影响的记录数：' + data);
                    this.getData();
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
            this.getRoles();
            this.form = G.copy(record);
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.getRoles();
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
                Api.admin.update(this.form.id , this.form , callback);
                return ;
            }
            Api.admin.store(this.form , callback);
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.val.modal = false;
            this.ins.avatar.clearAll();
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

    watch: {
        form (form , oldVal) {
            this.val.birthday = form.birthday;
            this.ins.avatar.render(form.avatar);
        } ,
    } ,
}
