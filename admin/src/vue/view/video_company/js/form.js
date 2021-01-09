
const form = {
    module_id: '' ,
    weight: 0 ,
    country_id: '' ,
    status: 1 ,
};

const owner = {
    id: 0 ,
    username: 'unknow' ,
};

const country = {
    id: 0 ,
    name: 'unknow' ,
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

            myValue: {
                show: false ,
                showUserSelector: false ,
            } ,

            dom: {} ,

            ins: {} ,

            owner: G.copy(owner) ,

            country: G.copy(country) ,

            modules: [] ,

        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {

        getModules () {
            this.pending('getModules' , true);
            Api.module
                .all()
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
                api: this.thumbApi(),
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
            this.pending('findById' , true);
            return new Promise((resolve , reject) => {
               Api.videoCompany
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
            this.setValue('show' , true);
            this.getModules();

            if (this.mode === 'add') {
                // 添加
                return ;
            }
            // 编辑
            this.findById(this.id)
                .then(() => {
                    this.owner      = this.form.user ? this.form.user : G.copy(owner);
                    this.country    = this.form.region ? this.form.region : G.copy(country);

                    this.ins.thumb.render(this.form.thumb);
                });
        } ,

        closeFormModal () {
            if (this.pending('submitEvent')) {
                this.message('warning' , '请求中...请耐心等待');
                return;
            }
            this.myValue.show   = false;
            this.modules    = [];
            this.form       = G.copy(form);
            this.owner      = G.copy(owner);
            this.country    = G.copy(country);
            this.ins.thumb.clearAll();
        } ,

        submitEvent () {
            const self = this;
            this.pending('submitEvent' , true);
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
                this.pending('submitEvent' , false);
                this.error();
            };
            if (this.mode === 'edit') {
                Api.videoCompany.update(this.form.id , this.form).then(thenCallback).finally(finalCallback);
                return ;
            }
            Api.videoCompany.store(this.form).then(thenCallback).finally(finalCallback);
        } ,

        userChangeEvent (res) {
            this.error({user_id: ''} , false);
            this.form.user_id   = res.id;
            this.owner          = res;
        } ,

        countryChangeEvent (res) {
            this.error({country_id: ''} , false);
            this.form.country_id   = res.id;
            this.country          = res;
        } ,

        showUserSelector () {
            this.$refs['user-selector'].show();
        } ,

        showCountrySelector () {
            this.$refs['country-selector'].show();
        } ,
    } ,
}
