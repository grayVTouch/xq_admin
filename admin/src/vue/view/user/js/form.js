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
               Api.user.show(id).then((res) => {
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

            if (this.mode === 'add') {
                // 添加
                return ;
            }
            // 编辑
            this.findById(this.id)
                .then(() => {
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
            this.form = G.copy(form);
            this.ins.avatar.clearAll();
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            this.form.birthday = this.val.birthday;
            const callback = (res) => {
                this.pending('submit' , false);
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
                Api.user.update(this.form.id , this.form).then(callback);
                return ;
            }
            Api.user.store(this.form).then(callback);
        } ,

        birthdayChangeEvent (date) {
            this.val.birthday = date;
        } ,
    } ,
}
