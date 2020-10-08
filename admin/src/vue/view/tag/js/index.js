
const form = {
    module_id: '' ,
    weight: 0 ,
    status: 0 ,
};


const users = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            center: TopContext.table.alignCenter ,
            width: TopContext.table.id ,
        } ,
        {
            title: '名称' ,
            key: 'username' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '头像' ,
            slot: 'avatar' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '创建时间' ,
            key: 'created_at' ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
        } ,
    ] ,
    current: {
        id: 0 ,
        username: 'unknow' ,
    } ,
    limit: TopContext.limit ,
    value: '' ,
    page: 1 ,
    total: 0 ,
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
                // 抽屉
                drawer: false ,
                modalForUser: false ,

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
                        title: '模块【id】',
                        slot: 'module_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '用户',
                        slot: 'user_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '使用次数' ,
                        key: 'count' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '状态',
                        slot: 'status',
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '失败原因',
                        key: 'fail_reason',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
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
                total: 0 ,
                page: 1 ,
                data: [] ,
            } ,
            search: {
                limit: TopContext.limit ,
                module_id: '' ,
            } ,
            form: G.copy(form)  ,

            modules: [] ,

            users: G.copy(users) ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
        this.getModules();
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

        getModules () {
            this.pending('getModules' , true);
            Api.module.all((msg , data , code) => {
                this.pending('getModules' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.modules = data;
            });
        } ,


        initDom () {
        } ,



        initIns () {

        } ,

        getData () {
            this.pending('getData' , true);
            Api.tag.index(this.search , (msg , data , code) => {
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
                Api.tag.destroyAll(idList , (msg , data , code) => {
                    if (code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.message('error' , msg);
                        return ;
                    }
                    G.invoke(callback , this , true);
                    this.message('success' , '操作成功' , '影响的记录数：' + data);
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

        // 获取当前编辑记录详情
        findById (id) {
            return new Promise((resolve , reject) => {
                this._val('findById' , true);
                Api.tag.show(id , (msg , data , code) => {
                    this._val('findById' , true);
                    if (code !== TopContext.code.Success) {
                        this.message('error' , msg);
                        reject();
                        return ;
                    }
                    this.form = data;
                    resolve();
                });
            });
        } ,

        editEvent (record) {
            this._val('modal' , true);
            this._val('mode' , 'edit');
            this.error();
            this.getModules();
            this.findById(record.id).then((res) => {
                this.users.current = this.form.user ? this.form.user : G.copy(users.current);
            });
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.error();
            this.getModules();
            this.form = G.copy(form);
            this.users = G.copy(users);
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
                Api.tag.update(this.form.id , this.form , callback);
                return ;
            }
            Api.tag.store(this.form , callback);
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

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search({
                value: this.users.value ,
                limit: this.users.limit ,
                page: this.users.page ,
            }, (msg , data , code) => {
                this.pending('searchUser' , false);
                if (code !== TopContext.code.Success) {
                    this.error({user_id: data});
                    return ;
                }
                this.users.total = data.total;
                this.users.page = data.current_page;
                this.users.data = data.data;
            });
        } ,

        userPageEvent (page) {
            this.users.page = page;
            this.searchUser();
        } ,

        searchUserEvent (e) {
            this.searchUser();
            this._val('modalForUser' , true);
        } ,

        updateUserEvent (row , index) {
            this.error({user_id: ''}, false);
            this.form.user_id = row.id;
            this._val('modalForUser', false);
            this.users.data = [];
            this.users.current = G.copy(row);
        },
    } ,
}
