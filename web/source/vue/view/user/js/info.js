import mixin from './mixin.js';

export default {
    name: "info" ,
    data () {
        return {
            val: {
                pending: {} ,
                error: {} ,
            } ,
            dom: {} ,
            ins: {} ,
            form: {} ,
        };
    } ,

    created () {
        this.user();
    } ,

    mixins: [
        mixin
    ] ,

    mounted () {
        this.$emit('focus-menu' , 'info');
        this.initDom();
        this.initIns();
        this.initEvent();
    } ,

    methods: {

        user () {
            this.pending('user' , true);
            Api.user.info((msg , data , code) => {
                this.pending('user' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.user();
                    });
                    return ;
                }

                this.form = {...data};
            });
        } ,

        initDom () {
            this.dom.uploaderMask = G(this.$refs['uploader-mask']);
        } ,

        initIns () {
            const self = this;
            this.ins.avatar = new Uploader(this.dom.uploaderMask.get(0) , {
                // 上传地址
                api: TopContext.fileApi ,
                // 上传字段
                field: 'file' ,
                // 模式：append-追加 override-覆盖
                mode: 'override' ,
                // 单文件上传
                multiple: false ,
                // 单个文件上传完成调用
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.avatar = data.data;
                } ,
                // 全部上传完成回调函数
                completed () {
                    console.log('文件上传完成');
                } ,
                // 清空后回调函数
                cleared: null ,
                // 文件上传超时时间，默认：0-不限制
                // 单位： s
                timeout: 0 ,
                // 是否启用清空所有的功能
                clear: false ,
                // 直接上传
                direct: true ,
            });
        } ,
        initEvent () {} ,

        submit () {
            if (this.pending('submit')) {
                return ;
            }
            const self = this;
            this.pending('submit' , true);
            Api.user.update(this.form , (msg , data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code);
                    return ;
                }
                this.message('操作成功');
            });
        } ,
    } ,

    watch: {
        form (newVal , oldVal) {
            this.ins.avatar.render(newVal.__avatar__);
        } ,
    } ,


}