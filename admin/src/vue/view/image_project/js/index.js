import Form from '../form.vue';

export default {
    name: "index",

    components: {
        'my-form': Form ,
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
                        width: 250 ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                        resizable: true ,
                        ellipsis: true ,
                        tooltip: true ,
                    } ,
                    {
                        title: '封面' ,
                        slot: 'thumb' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '用户【id】' ,
                        slot: 'user_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '模块【id】' ,
                        slot: 'module_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '分类【id】' ,
                        slot: 'category_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '类型' ,
                        key: '__type__' ,
                        minWidth: TopContext.table.type ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '关联主体【id】' ,
                        slot: 'image_subject_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '标签' ,
                        slot: 'tags' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '图片列表' ,
                        slot: 'images' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '状态' ,
                        key: '__status__' ,
                        slot: 'status' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                    {
                        title: '失败原因' ,
                        key: 'fail_reason' ,
                        minWidth: TopContext.table.desc ,
                        align: TopContext.table.alignCenter
                    } ,

                    {
                        title: '浏览次数' ,
                        key: 'view_count' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '获赞次数' ,
                        key: 'praise_count' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter
                    } ,

                    {
                        title: '描述' ,
                        key: 'description' ,
                        minWidth: TopContext.table.desc ,
                        align: TopContext.table.alignCenter ,
                        resizable: true ,
                        ellipsis: true ,
                        tooltip: true ,
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
                total: 0 ,
                page: 1 ,
                data: [] ,
            } ,

            search: {
                limit: TopContext.limit ,
                user_id: '' ,
                module_id: '' ,
                category_id: '' ,
                subject_id: '' ,
                status: '' ,
            } ,

            modules: [] ,

            categories: [] ,

            form: {}  ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
        this.getModules();
        // this.getCategories();
    } ,

    computed: {
        showBatchBtn () {
            return this.val.selectedIds.length > 0;
        } ,
    } ,

    methods: {

        initDom () {

        } ,



        initIns () {

        } ,

        getCategories (moduleId) {
            this.search.category_id = '';
            this.categories         = [];

            if (!G.isNumeric(moduleId)) {
                return ;
            }

            this.pending('getCategories' , true);

            Api.category.searchByModuleId(moduleId , (msg , data , code) => {
                this.pending('getCategories' , false);

                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }

                this.categories = data;
            });
        } ,

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

        getData () {
            this.pending('getData' , true);
            Api.image_project.index(this.search , (msg , data , code) => {
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

                v.images = v.images ? v.images : [];
                v.tags = v.tags ? v.tags : [];
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
                if (res) {
                    Api.image_project.destroyAll(idList , (msg , data , code) => {
                        if (code !== TopContext.code.Success) {
                            G.invoke(callback , this , false);
                            this.message('error' , msg);
                            return ;
                        }
                        G.invoke(callback , this , true);
                        this.message('success' , '操作成功' , '影响的记录数：' + data);
                        this.getData();
                    });
                    return ;
                }
                G.invoke(callback , this , false);
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
            this.form = G.copy(record);
            this.$nextTick(() => {
                this.$refs.form.openFormDrawer();
            });
        } ,

        addEvent () {
            this._val('mode' , 'add');
            this.form = {};
            this.$nextTick(() => {
                this.$refs.form.openFormDrawer();
            });
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (msg , data , code) => {
                this.pending('submit' , false);
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
                Api.image_project.update(this.form.id , this.form , callback);
                return ;
            }
            Api.image_project.store(this.form , callback);
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
    } ,
}
