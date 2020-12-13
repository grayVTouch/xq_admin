const form = {
    os: 'windows' ,
    is_default: 0 ,
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

            modules: [] ,

        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {

        initDom () {

        } ,

        initIns () {

        } ,

        findById (id) {
            this.pending('findById' , true);
            return new Promise((resolve , reject) => {
               Api.disk
                   .show(id)
                   .then((res) => {
                       if (res.code !== TopContext.code.Success) {
                           this.errorHandle(res.message);
                           reject();
                           return ;
                       }
                       this.form = res.data;
                       resolve();
                   }).finally(() => {
                       this.pending('findById' , false);
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

                });
        } ,

        closeFormModal () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.val.show   = false;
            this.modules    = [];
            this.form       = G.copy(form);
        } ,

        submitEvent () {
            const self = this;
            this.pending('submit' , true);
            const thenCallback = (res) => {
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
                this.error();
            };
            if (this.mode === 'edit') {
                Api.disk.update(this.form.id , this.form).then(thenCallback).finally(finalCallback);
                return ;
            }
            Api.disk.store(this.form).then(thenCallback).finally(finalCallback);
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
