const form = {
    sex: 'secret' ,
    // role_id: '' ,
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
    } ,

    computed: {
        title () {
            return this.mode === 'edit' ? '编辑' : '添加';
        } ,
    } ,

    data () {
        return {
            form: G.copy(form) ,

            val: {
                show: false ,
                birthday: '' ,
            } ,

            dom: {} ,

            ins: {} ,

            // 角色列表
            roles: [] ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {


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

        findById (id) {
            return new Promise((resolve , reject) => {
               Api.admin.show(id).then((res) => {
                   if (res.code !== TopContext.code.Success) {
                       this.errorHandle(res.message);
                       reject();
                       return ;
                   }
                   const data = res.data;
                   delete data.password;
                   this.form = data;
                   resolve();
               });
            });
        } ,

        openFormModal () {
            this._val('show' , true);
            this.getRoles();

            if (this.mode === 'add') {
                // 添加
                return ;
            }
            // 编辑
            this.findById(this.id).then(() => {
                // 做一些额外处理
                this.ins.avatar.render(this.form.avatar);
                this._val('birthday' , this.form.birthday);
            });
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this._val('show' , false);
            this._val('birthday' , '');
            this.form = G.copy(form);
            this.ins.avatar.clearAll();
        } ,

        getRoles () {
            this.pending('getRoles' , true);
            Api.role.all().then((res) => {
                this.pending('getRoles' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.roles = res.data;
            });
        } ,

        birthdayChangeEvent (date) {
            this.val.birthday = date;
        } ,

        filter () {
            const error = {};
            if (G.isEmptyString(this.form.username)) {
                error.username = '请填写用户名';
            }
            if (this.mode === 'add' && G.isEmptyString(this.form.password)) {
                error.username = '请填写密码';
            }
            if (this.form.role_id > 0) {
                error.username = '请填写';
            }
            return {
                status: G.isEmptyObject(error) ,
                error ,
            };
        } ,

        submitEvent () {
            const self = this;
            this.pending('submitEvent' , true);
            this.form.birthday = this.val.birthday;
            const filterRes = this.filter();
            if (!filterRes.status) {
                this.error(filterRes.error , true);
                this.errorHandle(G.getObjectFirstKeyMappingValue(filterRes.error));
                return ;
            }
            const callback = (res) => {
                this.pending('submitEvent' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.successHandle((keep) => {
                    this.$emit('on-success');
                    if (keep) {
                        return ;
                    }
                    self.closeFormModal();
                });
            };
            if (this.mode === 'edit') {
                Api.admin.update(this.form.id , this.form).then(callback);
                return ;
            }
            Api.admin.store(this.form).then(callback);
        } ,
    } ,
}
