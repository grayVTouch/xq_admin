const form = {
    type: 'view' ,
    p_id: 0 ,
    enable: 1 ,
    is_menu: 0 ,
    is_view: 1 ,
    weight: 0 ,
    s_ico: '' ,
    b_ico: '' ,
};

export default {
    name: "my-form" ,

    computed: {
        title () {
            return this.myValue.mode === 'edit' ? '编辑' : '添加';
        } ,
    } ,

    data () {
        return {
            myValue: {
                drawer: false,
                error: {},
                pending: {},
                attr: {
                    id: 'id',
                    floor: 'floor',
                    name: 'cn'
                },
            },
            ins: {},
            dom: {},

            // 权限列表（排除自身及其子类）
            permissions: [],

            form: G.copy(form),
        };
    } ,
    props: {
        data: {
            type: Object,
            required: true ,
        } ,
        mode: {
            type: String ,
            default: '' ,
        } ,
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {

        getPermissions (callback) {
            this.pending('getPermissions' , true);
            Api.admin_permission.index().then((res) => {
                this.pending('getPermissions' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.permissions = data;
                G.invoke(callback);
            });
        } ,

        initDom () {
            this.dom.sIco = G(this.$refs['s-ico']);
            this.dom.bIco = G(this.$refs['b-ico']);
        } ,

        initIns () {
            const self = this;
            this.ins.sIco = new Uploader(this.dom.sIco.get(0) , {
                api: this.imageApi() ,
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.s_ico = data.data;
                } ,
                cleared () {
                    self.form.s_ico = '';
                }
            });
            this.ins.bIco = new Uploader(this.dom.bIco.get(0) , {
                api: this.imageApi() ,
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.b_ico = data.data;
                } ,
                cleared () {
                    self.form.b_ico = '';
                } ,
            });
        } ,

        submitEvent () {
            const self = this;
            const callback = (res) => {
                this.pending('submitEvent' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.successModal((keep) => {
                    self.$emit('on-success');
                    if (keep) {
                        return ;
                    }
                    self.closeFormModal();
                });
            };
            this.pending('submitEvent' , true);
            if (this.mode === 'edit') {
                Api.admin_permission.update(this.form.id , this.form ,callback);
                return ;
            }
            Api.admin_permission.store(this.form).then(callback);
        } ,

        openFormModal () {
            this.setValue('drawer' , true);
            this.getPermissions(() => {
                if (this.mode === 'edit') {
                    this.permissions = this.getPermissionExcludeSelfAndChildrenById(this.form.id);
                }
            });
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
                this.message('warning' ,'请求中...请耐心等待');
                return ;
            }
            this.setValue('drawer' , false);
            this.permissions = [];
            this.form = G.copy(form);
            this.ins.sIco.clearAll();
            this.ins.bIco.clearAll();
        } ,

        // 排除给定 id 及其子类
        getPermissionExcludeSelfAndChildrenById (id) {
            const exclude = G.t.childrens(id , this.permissions , null , true , false);
            const exclude_ids = [];
            exclude.forEach((v) => {
                exclude_ids.push(v.id);
            });
            const res = [];
            this.permissions.forEach((v) => {
                if (G.contain(v.id , exclude_ids)) {
                    return ;
                }
                res.push(v);
            });
            return res;
        } ,
    } ,

    watch: {
        data (data) {
            if (G.isEmptyObject(data)) {
                this.form = G.copy(form);
            } else {
                this.form = data;
            }
            this.ins.sIco.render(this.form.__s_ico__);
            this.ins.bIco.render(this.form.__b_ico__);
        } ,

        form: {
            deep: true ,
            handler (form) {

            } ,
        } ,
    } ,
}
