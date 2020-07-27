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
            default () {
                return {};
            } ,
        } ,

        mode: {
            type: String ,
            default: '' ,
        } ,

        permission: {
            type: Array ,
            default () {
                return [];
            } ,
        }
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
                api: TopContext.fileApi ,
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
            const callback = (msg , data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.$emit('on-error');
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.successHandle((keep) => {
                    self.$emit('on-success');
                    if (!keep) {
                        self.closeFormDrawer();
                    }
                });
            };
            this.pending('submit' , true);
            if (this.mode === 'edit') {
                Api.admin_permission.update(this.form.id , this.form ,callback);
                return ;
            }
            Api.admin_permission.store(this.form , callback);
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' ,'请求中...请耐心等待');
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