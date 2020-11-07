import myForm from '../form.vue';

export default {
    name: "index",

    components: {
        'my-form': myForm ,
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
            releaseTime: '' ,
            endTime: '' ,
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
                        slot: 'name' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '封面' ,
                        slot: 'thumb' ,
                        minWidth: TopContext.table.image ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'left' ,
                    } ,
                    {
                        title: '评分' ,
                        key: 'score' ,
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter
                    } ,
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
                        title: '所属分类' ,
                        slot: 'category_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '视频系列' ,
                        slot: 'video_series_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '视频制作公司' ,
                        slot: 'video_company_id' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '视频数',
                        key: 'count',
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '播放数',
                        key: 'play_count',
                        minWidth: TopContext.table.number ,
                        align: TopContext.table.alignCenter,
                    },
                    {
                        title: '标签' ,
                        slot: 'tags' ,
                        minWidth: TopContext.table.name ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '描述' ,
                        key: 'description' ,
                        minWidth: TopContext.table.desc ,
                        align: TopContext.table.alignCenter ,
                    } ,
                    {
                        title: '完结状态' ,
                        slot: 'status' ,
                        minWidth: TopContext.table.status ,
                        align: TopContext.table.alignCenter ,
                        fixed: 'right' ,
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
                        title: '发布年份' ,
                        key: 'release_year' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '发布日期' ,
                        key: 'release_date' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter
                    } ,
                    {
                        title: '完结日期' ,
                        key: 'end_date' ,
                        minWidth: TopContext.table.time ,
                        align: TopContext.table.alignCenter
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
            videoSeries: [] ,
            videoCompany: [] ,
            search: {
                limit: TopContext.limit ,
                video_series_id: '' ,
                video_company_id: '' ,
                module_id: '' ,
            } ,
            form: {} ,
            modules: [] ,
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

        resetForm () {
            this.form = G.copy(form , true);
        } ,

        getData () {
            this.pending('getData' , true);
            Api.video_project.index(this.search , (msg , data , code) => {
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
                Api.video_project.destroyAll(idList , (msg , data , code) => {
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
            this.error();
            this.form = G.copy(record);
            this.getModules();
            this.$nextTick(() => {
                this.$refs.form.openFormDrawer();
            });
        } ,

        addEvent () {
            this._val('mode' , 'add');
            this.error();
            this.getModules();
            this.form = {};
            this.$nextTick(() => {
                this.$refs.form.openFormDrawer();
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

        setReleaseTimeEvent () {} ,
        setEndTimeEvent () {} ,
    } ,
}
