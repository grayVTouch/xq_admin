const form = {
    os: 'windows' ,
    // 是否默认 0-否 1-是
    is_default: 1 ,
    // 是否创建软连接 0-否 1-是
    is_linked: 1 ,
    path: '' ,
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
                    this.successHandle();
                    this.closeFormModal();
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
