import mixin from './mixin.js';
export default {
    name: "info" ,
    data () {
        return {
            val: {
                pending: {} ,
                error: {} ,
            } ,
            form: {} ,
        };
    } ,

    mixins: [
        mixin
    ] ,

    mounted () {
        this.$emit('focus-menu' , 'password');
    } ,

    methods: {

        submit () {
            if (this.pending('submit')) {
                return ;
            }
            const self = this;
            this.pending('submit' , true);
            Api.user.updatePasswordInLogged(this.form , (data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(data , code);
                    return ;
                }
                this.message('操作成功');
            });
        } ,
    } ,


}