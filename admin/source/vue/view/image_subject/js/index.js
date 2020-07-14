import Form from '../form.vue';

const form = {
    type: 'misc' ,
    user_id: 0 ,
    module_id: 0 ,
    category_id: 0 ,
    subject_id: 0 ,
    view_count: 0  ,
    praise_count: 0 ,
    weight: 0 ,
    __tag__: [] ,
    status: 0 ,
    images: [] ,
    tags: [] ,
};

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
                        width: TopContext.table.id ,
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
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '用户【id】' ,
                        slot: 'user_id' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '模块【id】' ,
                        slot: 'module_id' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '分类【id】' ,
                        slot: 'category_id' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '类型' ,
                        key: '__type__' ,
                        width: TopContext.table.type ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '关联主体【id】' ,
                        slot: 'subject_id' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '标签' ,
                        slot: 'tag' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '图片列表' ,
                        slot: 'images' ,
                        width: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '状态' ,
                        key: '__status__' ,
                        slot: 'status' ,
                        width: TopContext.table.status ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '失败原因' ,
                        key: 'fail_reason' ,
                        width: TopContext.table.desc ,
                        align: TopContext.table.alignCenter
                    } ,

                    {
                        title: '浏览次数' ,
                        key: 'view_count' ,
                        width: TopContext.table.number ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '获赞次数' ,
                        key: 'praise_count' ,
                        width: TopContext.table.number ,
                        align: TopContext.table.alignCenter
                    } ,

                    {
                        title: '描述' ,
                        key: 'description' ,
                        width: TopContext.table.desc ,
                        align: TopContext.table.alignCenter ,
                        resizable: true ,
                        ellipsis: true ,
                        tooltip: true ,
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
                total: 0 ,
                page: 1 ,
                data: [] ,
            } ,
            search: {
                limit: this.$store.state.context.limit ,
            } ,
            form: {...form}  ,
            categories: [] ,
            modules: [] ,
            topTags: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
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

        initDom () {

        } ,



        initIns () {

        } ,

        moduleChanged (moduleId) {
            this.getCategoriesData(moduleId);
            this.getTopTags(moduleId);
        } ,

        getCategoriesData (moduleId , callback) {
            Api.category.searchByModuleId(moduleId , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    G.invoke(callback , null , false);
                    return ;
                }
                this.categories = data;
                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,

        getModulesData (callback) {
            Api.module.all((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    G.invoke(callback , null , false);
                    return ;
                }
                this.modules = data;
                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,

        getTopTags (moduleId) {
            Api.tag.topByModule(moduleId , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , data);
                    return ;
                }
                this.topTags = data;
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.image_subject.index(this.search , (data , code) => {
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
                if (res) {
                    Api.image_subject.destroyAll(idList , (data , code) => {
                        if (code !== TopContext.code.Success) {
                            G.invoke(callback , this , false);
                            this.message('error' , data);
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
            this._val('drawer' , true);
            this._val('mode' , 'edit');
            this.error();
            this.form = {...record};
            this.getModulesData();
            this.getCategoriesData(record.module_id);
            this.getTopTags(record.module_id);
        } ,

        addEvent () {
            this._val('drawer' , true);
            this._val('mode' , 'add');
            this.error();
            this.form = {...form};
            this.getModulesData();
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const callback = (data , code) => {
                this.pending('submit' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandle(data);
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
                Api.image_subject.update(this.form.id , this.form , callback);
                return ;
            }
            Api.image_subject.store(this.form , callback);
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