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
               Api.admin.show(id , (msg , data , code) => {
                   if (code !== TopContext.code.Success) {
                       this.errorHandle(msg);
                       reject();
                       return ;
                   }
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
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this._val('show' , false);
            this._val('birthday' , '');
            this.form = G.copy(form);
            this.ins.avatar.clearAll();
        } ,

        setDate (date) {
            this.form.birthday = date;
        } ,

        getRoles () {
            this.pending('getRoles' , true);
            Api.role.all((msg , data , code) => {
                this.pending('getRoles' , false);
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.roles = data;
            });
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
                    this.$emit('on-success');
                    if (keep) {
                        return ;
                    }
                    self.closeFormModal();
                });
            };
            if (this.mode === 'edit') {
                Api.admin.update(this.form.id , this.form , callback);
                return ;
            }
            Api.admin.store(this.form , callback);
        } ,
    } ,
}
