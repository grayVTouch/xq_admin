import Form from '../form.vue';

export default {
    name: "index",

    components: {
        'my-form': Form ,
    } ,

    data () {
        return {
            dom: {} ,
            ins: {} ,
            val: {
                pending: {} ,
                error: {} ,
                // edit-编辑 add-添加
                mode: '' ,
                selectedIds: [] ,
                drawer: false ,
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
                        width: 250 ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                        resizable: true ,
                        ellipsis: true ,
                        tooltip: true ,
                    } ,
                    {
                        title: '视频索引' ,
                        key: 'index' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '封面' ,
                        slot: 'thumb' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '程序截取封面' ,
                        slot: 'thumb_for_program' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '简略预览' ,
                        slot: 'simple_preview' ,
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '完整预览' ,
                        slot: 'preview' ,
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '时长' ,
                        key: '__duration__' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter ,
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
                        slot: 'video_subject_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '处理状态' ,
                        slot: 'process_status' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
                    } ,
                    {
                        title: '审核状态' ,
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
                        title: '反对次数' ,
                        key: 'against_count' ,
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
                        key: 'create_time' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '操作' ,
                        slot: 'action' ,
                        minWidth: TopContext.table.action + 80 ,
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
                video_subject_id: '' ,
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
                    this.message('error' , data);
                    return ;
                }

                this.categories = data;
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.video.index(this.search , (msg , data , code) => {
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
                this.pending(`retry_${v.id}` , false);
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
                    Api.video.destroyAll(idList , (msg , data , code) => {
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
                Api.video.update(this.form.id , this.form , callback);
                return ;
            }
            Api.video.store(this.form , callback);
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

        restartPlayVideo: function(e){
            const tar = e.currentTarget;
            tar.currentTime = 0;
            tar.play();
        } ,

        retryProcessVideosEvent () {
            this.retryProcessVideos(this.val.selectedIds);
        } ,

        retryProcessVideoEvent (record) {
            const pending = 'retry_' + record.id;
            this.pending(pending , true);
            this.retryProcessVideo(record.id , () => {
                this.pending(pending , false);
            });
        } ,

        retryProcessVideo (id , callback) {
            this.retryProcessVideos([id] , callback)
        } ,

        retryProcessVideos (ids , callback) {
            this.pending('retryProcessVideos' , true);
            Api.video.retryProcessVideo(ids , (msg , data , code) => {
                this.pending('retryProcessVideos' , false);
                if (code !== TopContext.code.Success) {
                    G.invoke(callback , null , false);
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.getData();
                G.invoke(callback , null , true);
            })
        } ,


    } ,
}