const form = {
    os: 'windows' ,
    is_default: 1 ,
};

export default {
    name: "my-form" ,

    props: {

    } ,

    computed: {
        title () {
            return this.mode === 'edit' ? '编辑' : '添加';
        } ,
    } ,

    data () {
        return {
            form: G.copy(form) ,

            myValue: {
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

        openFormModal () {
            this.setValue('show' , true);
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.myValue.show   = false;
            this.modules    = [];
            this.form       = G.copy(form);
            this.error();
        } ,


        filter (form) {
            const error = {};
            if (G.isEmptyString(form.path)) {
                error.path = '请填写目录真实路径';
            }
            if (G.isEmptyString(form.prefix)) {
                error.prefix = '请填写前缀';
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
            this.pending('submitEvent' , true);
            Api.disk
                .store(form)
                .then((res) => {
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
                })
                .finally(() => {
                    this.pending('submitEvent' , false);
                    this.error();
                });
        } ,

        userChangeEvent (res) {
            this.error({user_id: ''} , false);
            this.form.user_id   = res.id;
            this.owner          = res;
        } ,

        showUserSelector () {
            this.$refs['user-selector'].show();
        } ,

        showResourceSelector () {
            this.$refs['resource-selector'].show();
        } ,

        resourceChangedEvent (path) {
            this.form.path = path;
        } ,
    } ,
}
