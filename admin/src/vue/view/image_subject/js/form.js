const form = {
    module_id: '' ,
    weight: 0 ,
    status: 0 ,

};

const attr = [
    {
        field: '' ,
        value: '' ,
    }
];

const owner = {
    id: 0 ,
    username: 'unknow' ,
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
                showUserSelector: false ,
            } ,

            dom: {} ,

            ins: {} ,

            // 用户模块
            modules: [] ,

            // 用户列表
            owner:  G.copy(owner) ,

            attr ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getModules();
    } ,

    methods: {

        getModules () {
            this.pending('getModules' , true);
            Api.module.all()
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
            this.dom.thumb = G(this.$refs.thumb);
        } ,

        initIns () {
            const self = this;
            this.ins.thumb = new Uploader(this.dom.thumb.get(0) , {
                api: this.thumbApi() ,
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

        findById (id) {
            return new Promise((resolve , reject) => {
               Api.imageSubject.show(id).then((res) => {
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
            this.getModules();
            if (this.mode === 'add') {
                // 添加
                return ;
            }
            // 编辑
            this.findById(this.id).then(() => {
                // 做一些额外处理
                this.ins.thumb.render(this.form.thumb);
                this.owner = this.form.user ? this.form.user : G.copy(owner);
                this.attr = G.jsonDecode(this.form.attr);
            });
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this._val('show' , false);
            this.form = G.copy(form);
            this.attr = G.copy(attr);
            this.owner = G.copy(owner);
            this.ins.thumb.clearAll();
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const thenCallback = (res) => {
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
            const finalCallback = () => {
                this.pending('submit' , false);
            };
            this.form.attr = G.jsonEncode(this.attr);
            if (this.mode === 'edit') {
                Api.imageSubject.update(this.form.id , this.form).then(thenCallback).finally(finalCallback);
                return ;
            }
            Api.imageSubject.store(this.form).then(thenCallback).finally(finalCallback);
        } ,

        userChangeEvent (res) {
            this.error({user_id: ''} , false);
            this.form.user_id   = res.id;
            this.owner          = res;
        } ,

        showUserSelector () {
            this.$refs['user-selector'].show();
        } ,
    } ,
}
