export default {
    name: 'login' ,
    data () {
        return {
            form: {} ,
            dom: {} ,
            ins: {} ,
            val: {
                // 是否显示密码
                showPassword: false ,
                // 表单错误信息
                error: {} ,
                // 消息
                message: {
                    text: '' ,
                    class: '' ,
                } ,
                // 图形验证码
                captcha: {} ,
                // 关注状态
                focus: {} ,
                // 用户头像
                avatar: '' ,
                // 网络请求
                pending: {
                    submit: false ,
                } ,
                // 请求状态
                request: {
                    submit: true
                } ,
            } ,
        };
    } ,

    mounted () {
        this.captcha();
    } ,

    methods: {

        topMessage (text = '' , classname = '') {
            this._val('message' , Object.assign({} , {
                text ,
                class: classname
            }));
        } ,



        captcha () {
            Api.misc.captcha((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    // this.error({captcha_code: '获取图形验证码失败，请稍后重新点击验证码再次尝试'} , false);
                    return ;
                }
                this.val.captcha = data;
                this.form.captcha_key = data.key;
            })
        } ,

        focusEvent (e) {
            const tar = G(e.currentTarget);
            const name = tar.data('name');
            this.val.focus = {...this.val.focus , ...{[name]: true}};
        } ,

        blurEvent (e) {
            const tar = G(e.currentTarget);
            const name = tar.data('name');
            this.val.focus = {...this.val.focus , ...{[name]: false}};
        } ,

        usernameInputEvent () {
            this.error({username: ''} , false);
            Api.login.avatar({username: this.form.username} , (msg , data , code) => {
                this.val.avatar = '';
                if (code !== TopContext.code.Success) {
                    return ;
                }
                this.val.avatar = data;
            });
        } ,

        submitEvent () {
            if (!this.request('submit') || this.pending('submit')) {
                return ;
            }
            this.pending('submit' , true);
            Api.login.login(this.form , (msg , data , code) => {
                this.pending('submit' , false);
                this.error();
                this.topMessage();
                this.captcha();
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.request('submit' , false);
                this.topMessage('登录成功，进入首页中...' , 'run-green');
                G.s.set('token' , data);
                G.setTimeout(() => {
                    this.push({name: 'home'});
                } , 1000);
            });
        } ,
    }
};