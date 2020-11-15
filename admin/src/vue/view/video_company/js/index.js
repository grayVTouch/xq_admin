
const form = {
    module_id: '' ,
    weight: 0 ,
    country_id: '' ,
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

const countries = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            minWidth: TopContext.table.id ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '名称' ,
            key: 'name' ,
            minWidth: TopContext.table.name ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '创建时间' ,
            key: 'created_at' ,
            minWidth: TopContext.table.time ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            center: TopContext.table.alignCenter ,
        } ,
    ] ,
    current: {
        id: 0 ,
        name: 'unknow' ,
    },
    total: 0 ,
    page: 1 ,
    value: '' ,
    type: 'country' ,
    limit: 10 ,
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
                modalForCountry: false ,
                // 用户模态框
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
                        title: '封面',
                        slot: 'thumb',
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter,
                        fixed: 'left' ,
                    },
                    {
                        title: '用户',
                        slot: 'user_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '模块【id】',
                        slot: 'module_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '国家【id】',
                        slot: 'country_id',
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter,
                    },
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
                        title: '描述',
                        key: 'description',
                        minWidth: TopContext.table.desc ,
                        align: TopContext.table.alignLeft,
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
                country_id: '' ,
            } ,

            form: G.copy(form) ,

            // 国家
            countries: G.copy(countries) ,

            countriesInList: [] ,

            // 模块
            modules: [] ,

            users: G.copy(users) ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
        this.getModules();
        this.getCountries();
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
            Api.module.all().then((res) => {
                this.pending('getModules' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.modules = data;
            });
        } ,

        getCountries () {
            this.pending('getCountries' , true);
            Api.region.country().then((res) => {
                this.pending('getCountries' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.countriesInList = data;
            });
        } ,


        initDom () {
            this.dom.thumb = G(this.$refs.thumb);
        } ,



        initIns () {
            const self = this;
            this.ins.thumb = new Uploader(this.dom.thumb.get(0) , {
                api: this.thumbApi(),
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.thumb = data.data;
                } ,
                cleared () {
                    self.form.thumb = '';
                } ,
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.video_company.index(this.search , (res) => {
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
                Api.video_company.destroyAll(idList , (res) => {
                    if (res.code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.errorHandle(res.message);
                        return ;
                    }
                    G.invoke(callback , this , true);
                    this.message('success' , '操作成功' , '影响的记录数：' + data);
                    this.getData();
                });
            });
        } ,

        selectionChangeEvent (data) {
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
            this._val('mode' , 'edit');
            this.ins.thumb.render(this.form.thumb);
            this.getModules();
            this.openFormModal();
            this.findById(record.id , (res) => {
                if (!res) {
                    return ;
                }
                this.users.current      = this.form.user ? this.form.user : G.copy(users.current);
                this.countries.current  = this.form.region ? this.form.region : G.copy(countries.current);
            });
        } ,

        // 获取当前编辑记录详情
        findById (id , callback) {
            this._val('findById' , true);
            Api.video_company.show(id , (res) => {
                this._val('findById' , true);
                if (res.code !== TopContext.code.Success) {
                    G.invoke(callback , null , false);
                    this.errorHandle(res.message);
                    return ;
                }
                this.form = data;
                G.invoke(callback , null , true);
            });
        } ,

        addEvent () {
            this._val('modal' , true);
            this._val('mode' , 'add');
            this.getModules();
            this.error();
            this.form = G.copy(form);
            this.countries = G.copy(countries);
            this.ins.thumb.clearAll();
            this.openFormModal();
        } ,

        openFormModal () {
            this._val('modal' , true);
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this._val('modal' , false);
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (res) => {
                this.pending('submit' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
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
                Api.video_company.update(this.form.id , this.form).then(callback);
                return ;
            }
            Api.video_company.store(this.form).then(callback);
        } ,

        searchEvent () {
            this.search.page = 1;
            this.getData();
        } ,

        pageEvent (page) {
            this.search.page = page;
            this.getData();
        } ,

        countryPageEvent (page) {
            this.countries.page = page;
            this.searchCountry();
        } ,

        searchCountry () {
            this.pending('searchCountry' , true);
            Api.region.search({
                value: this.countries.value ,
                type: this.countries.type ,
                limit: this.countries.limit ,
                page: this.countries.page ,
            } , (res) => {
                this.pending('searchCountry' , false);
                if (res.code !== TopContext.code.Success) {
                    this.error({country_id: data} , false);
                    return ;
                }
                this.countries.page = data.current_page;
                this.countries.total = data.total;
                this.countries.data = data.data;
            });
        } ,

        searchCountryEvent (e) {
            this.searchCountry();
            this._val('modalForCountry' , true);
        } ,

        updateCountryEvent (row , index) {
            this.error({country_id: ''} , false);
            this.form.country_id = row.id;
            this.form.country   = row.name;
            this.countries.current = row;
            this._val('modalForCountry' , false);
            this.countries.data = [];
        } ,

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search({
                value: this.users.value ,
                limit: this.users.limit ,
                page: this.users.page ,
            }, (res) => {
                this.pending('searchUser' , false);
                if (res.code !== TopContext.code.Success) {
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
