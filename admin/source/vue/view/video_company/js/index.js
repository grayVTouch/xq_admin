
const form = {
    module_id: '' ,
    weight: 0 ,
    country_id: '' ,
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
            key: 'create_time' ,
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

        showDestroyAllBtn () {
            return this.val.selectedIds.length > 0;
        } ,
    } ,

    methods: {

        getModules () {
            this.pending('getModules' , true);
            Api.module.all((msg , data , code) => {
                this.pending('getModules' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.modules = data;
            });
        } ,

        getCountries () {
            this.pending('getCountries' , true);
            Api.region.country((msg , data , code) => {
                this.pending('getCountries' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
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
            Api.video_company.index(this.search , (msg , data , code) => {
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
                Api.video_company.destroyAll(idList , (msg , data , code) => {
                    if (code !== TopContext.code.Success) {
                        G.invoke(callback , this , false);
                        this.message('error' , data);
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

        editEvent (record) {
            this._val('mode' , 'edit');
            this.form = record;
            this.ins.thumb.render(this.form.__thumb__);
            this.getModules();
            this.countries.current = {
                id:   record.country_id ,
                name: record.country ,
            };
            this.openFormModal();
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
                Api.video_company.update(this.form.id , this.form , callback);
                return ;
            }
            Api.video_company.store(this.form , callback);
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
            } , (msg , data , code) => {
                this.pending('searchCountry' , false);
                if (code !== TopContext.code.Success) {
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
        
    } ,
}