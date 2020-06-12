export default {
    name: "my-form" ,

    data () {
        return {
            val: {
                error: {} ,
                pending: {} ,
                attr: {
                    id: 'id',
                    floor: 'floor',
                    name: 'cn'
                } ,
            } ,
            ins: {} ,
            dom: {} ,
            show: false ,
        };
    } ,

    model: {
        name: 'value' ,
        event: 'change' ,
    } ,

    props: {
        value: {
            type: Boolean ,
            default: false ,
        } ,

        title: {
            type: String ,
            default: '' ,
        } ,

        form: {
            type: Object ,
            default: {} ,
        } ,

        mode: {
            type: String ,
            default: '' ,
        } ,

        data: {
            type: Array ,
            default: [] ,
        } ,
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {

        initDom () {
            this.dom.sIco = G(this.$refs['s-ico']);
            this.dom.bIco = G(this.$refs['b-ico']);
        } ,

        initIns () {
            const self = this;
            this.ins.sIco = new Uploader(this.dom.sIco.get(0) , {
                api: TopContext.fileApi ,
                field: 'file' ,
                mode: 'override' ,
                multiple: false ,
                direct: true ,
                clear: false ,
                uploaded (file , data , code) {
                    if (code !== TopContext.successCode) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.s_ico = data;
                } ,
            });
            this.ins.bIco = new Uploader(this.dom.bIco.get(0) , {
                api: TopContext.fileApi ,
                field: 'file' ,
                mode: 'override' ,
                multiple: false ,
                direct: true ,
                clear: false ,
                uploaded (file , data , code) {
                    if (code !== TopContext.successCode) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.b_ico = data;
                } ,
            });
        } ,

        submitEvent () {
            const self = this;
            const callback = (data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.successCode) {
                    this.$emit('on-error');
                    if (G.isString(data)) {
                        this.message('error' , data);
                        return ;
                    }
                    this.message('error' , '操作失败，请检查表单');
                    this.val.error = data;
                    this.error(data);
                    return ;
                }
                this.modal('confirm' , '操作成功，继续操作？' , '' , null , {
                    onOk () {
                        self.$emit('on-success');
                    } ,
                    onCancel () {
                        self.closeFormDrawer();
                        self.$emit('on-success');
                    } ,
                });
            };
            this.pending('submit' , true);
            if (this.mode === 'edit') {
                Api.adminPermission.update(this.form.id , this.form ,callback);
                return ;
            }
            Api.adminPermission.store(this.form , callback);
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' ,'表单请求中...请耐心等待');
                return ;
            }
            this.show = false;
            this.ins.sIco.clearAll();
            this.ins.bIco.clearAll();
        } ,
    } ,

    watch: {
        show (newVal , oldVal) {
            this.$emit('change' , newVal);
        } ,

        value: {
            immediate: true ,
            handler (newVal , oldVal) {
                this.show = newVal;
            }
        } ,

        form (newVal , oldVal) {
            this.form = newVal;
            this.ins.sIco.render(this.form.__s_ico__);
            this.ins.bIco.render(this.form.__b_ico__);
        } ,
    } ,
}