const form = {
    module_id: '' ,
    weight: 0 ,
    status: 1 ,
    is_enabled: 1 ,
    value: '' ,
    p_id: '' ,
};

const category = {
    id: 0 ,
    name: 'unknow' ,
};

export default {
    name: "my-form" ,

    props: {
        id: {
            type: [Number , String] ,
            required: true ,
        } ,

        mode: {
            type: String ,
            default: 'add' ,
        } ,

        addMode: {
            type: String ,
            default: 'add_next' ,
        } ,
    } ,

    computed: {
        title () {
            return this.mode === 'edit' ? '编辑' : '添加';
        } ,
    } ,

    data () {
        return {
            form: G.copy(form),

            myValue: {
                show: false,
                showModuleSelector: false,
                showTypeSelector: false,
                /**
                 * module - 选择模块
                 * form - 输入表单
                 */
                step: 'module',
            },

            dom: {},

            ins: {},

            modules: [],

            // 分类
            navs: [],

            category: G.copy(category),

        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {

        getModules () {
            this.pending('getModules' , true);
            Api.module
                .all()
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    this.modules = res.data;
                })
                .finally(() => {
                    this.pending('getModules' , false);
                });
        } ,

        initDom () {

        } ,

        initIns () {

        } ,

        findById (id) {
            this.pending('findById' , true);
            return new Promise((resolve , reject) => {
               Api.nav
                   .show(id)
                   .then((res) => {
                       if (res.code !== TopContext.code.Success) {
                           this.errorHandle(res.message);
                           reject();
                           return ;
                       }
                       resolve(res.data);
                   }).finally(() => {
                       this.pending('findById' , false);
                   });
            });
        } ,

        openFormModal () {
            this.getModules();
            if (this.mode === 'add') {
                // 添加
                if (this.addMode === 'add_next') {
                    this.handleFormStep();
                } else {
                    this.handleModuleStep();
                }
            } else {
                this.handleFormStep();
            }
        } ,

        // 模块处理
        handleModuleStep () {
            // 清空
            this.form.module_id = '';
            this.myValue.step = 'module';
            this.myValue.showModuleSelector = true;
        } ,

        // 表单处理
        handleFormStep () {
            this.myValue.step = 'form';
            this.myValue.show = true;
            if (this.mode === 'edit') {
                this.findById(this.id)
                    .then((res) => {
                        this.form = res;
                        this.getNavs();
                    });
            } else {
                if (this.addMode === 'add_next') {
                    this.findById(this.id)
                        .then((res) => {
                            this.form.module_id = res.module_id;
                            this.form.type = res.type;
                            this.form.p_id = res.id;

                            this.getNavs();
                        });
                }
            }
        } ,

        nextStepAtType () {
            if (this.form.module_id  < 1) {
                this.errorHandle('请选择模块');
                return ;
            }
            this.form.type = '';
            this.myValue.step = 'type';
            this.myValue.showModuleSelector = false;
            this.myValue.showTypeSelector = true;
        } ,

        nextStepAtForm () {
            if (this.form.module_id  < 1) {
                this.errorHandle('请选择模块');
                return ;
            }
            if (G.isEmptyString(this.form.type)) {
                this.errorHandle('请选择类型');
                return ;
            }
            this.myValue.showModuleSelector = false;
            this.myValue.showTypeSelector = false;
            // 获取导航菜单
            this.getNavs();
            // 表单处理
            this.handleFormStep();
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.myValue.step = 'module';
            this.myValue.showModuleSelector = false;
            this.myValue.show   = false;
            this.modules    = [];
            this.navs = [];
            this.form       = G.copy(form);
            this.category   = G.copy(category);
            this.error();
        } ,

        filter (form) {
            const error = {};
            if (G.isEmptyString(form.name)) {
                error.name = '请填写名称';
            }
            if (G.isEmptyString(form.type)) {
                error.type = '请选择类型';
            }
            if (!G.isNumeric(form.value)) {
                error.value = '请选择分类';
            }
            if (!G.isNumeric(form.module_id)) {
                error.module_id = '请选择模块';
            }
            if (!G.isNumeric(form.p_id)) {
                error.p_id = '请选择上级导航';
            }
            return {
                status: G.isEmptyObject(error) ,
                error ,
            };
        } ,

        submitEvent () {
            const self = this;
            const form = G.copy(this.form);
            const filterRes = this.filter(form);
            if (!filterRes.status) {
                this.error(filterRes.error , true);
                this.errorHandle(G.getObjectFirstKeyMappingValue(filterRes.error));
                return ;
            }
            const thenCallback = (res) => {
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.successModal((keep) => {
                    this.$emit('on-success');
                    if (keep) {
                        return ;
                    }
                    self.closeFormModal();
                });
            };
            const finalCallback = () => {
                this.pending('submitEvent' , false);
                this.error();
            };
            this.pending('submitEvent' , true);
            if (this.mode === 'edit') {
                Api.nav.update(form.id , form).then(thenCallback).finally(finalCallback);
                return ;
            }
            Api.nav.store(form).then(thenCallback).finally(finalCallback);
        } ,

        getNavsExcludeSelfAndChildrenByIdAndData (id , data) {
            const selfAndChildren = G.t.childrens(id , data , null , true , false);
            const selfAndChildrenIds = [];
            selfAndChildren.forEach((v) => {
                selfAndChildrenIds.push(v.id);
            });
            const res = [];
            data.forEach((v) => {
                if (G.contain(v.id , selfAndChildrenIds)) {
                    return ;
                }
                res.push(v);
            });
            return res;
        } ,

        getNavs () {
            if (this.form.module_id <= 0) {
                return ;
            }
            if (G.isEmptyString(this.form.type)) {
                return ;
            }
            this.pending('getNavs' , true);
            Api.nav
                .search({
                    module_id: this.form.module_id ,
                    type: this.form.type ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    this.navs = this.getNavsExcludeSelfAndChildrenByIdAndData(this.form.id , res.data);
                })
                .finally(() => {
                    this.pending('getNavs' , false);
                });
        } ,

        moduleChangedEvent (moduleId) {
            this.myValue.error.module_id = '';
            if (!G.isNumeric(moduleId)) {
                return ;
            }
            this.form.p_id = '';
            this.form.module_id = moduleId;
            this.getNavs();
        } ,

        typeChangeEvent () {
            this.myValue.error.type = '';
            this.getNavs();
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

        categoryChangedEvent (row) {
            this.category = {
                id: row.id ,
                name: row.name ,
            };
            this.myValue.error.value = '';
            this.form.value = row.id;

            console.log('category row id' , row.id , row);
        } ,

        openCategorySelector () {
            this.$refs['category-selector'].show();
        } ,
    } ,
}
