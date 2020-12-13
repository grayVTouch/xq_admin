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
            Api.misc.captcha().then((res) => {
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    return ;
                }
                this.val.captcha = res.data;
                this.form.captcha_key = res.data.key;
            });
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
            Api.login.avatar({username: this.form.username} , (res) => {
                this.val.avatar = '';
                if (res.code !== TopContext.code.Success) {
                    return ;
                }
                this.val.avatar = data;
            });
        } ,

        filter () {
            const error = {};
            if (G.isEmptyString(this.form.username)) {
                error.username = '请填写用户名';
            }
            if (G.isEmptyString(this.form.password)) {
                error.password = '请填写密码';
            }
            if (G.isEmptyString(this.form.captcha_code)) {
                error.captcha_code = '请填写验证码';
            }
            return {
                status: G.isEmptyObject(error) ,
                error ,
            };
        } ,

        submitEvent () {
            if (!this.request('submit') || this.pending('submitEvent')) {
                return ;
            }
            const filterRes = this.filter();
            if (!filterRes.status) {
                this.error(filterRes.error);
                // this.errorHandle(G.getObjectFirstKeyMappingValue(filterRes.error));
                return ;
            }
            this.pending('submitEvent' , true);
            Api.login.login(this.form).then((res) => {
                    this.pending('submitEvent' , false);
                    this.error();
                    this.topMessage();
                    this.captcha();
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    this.request('submit' , false);
                    this.topMessage('登录成功，进入首页中...' , 'run-green');
                    G.cookie.set('token' , res.data);
                    G.setTimeout(() => {
                        // 延迟一些时间跳转到首页
                        // 给人更好的衔接感
                        this.push({name: 'home'});
                    } , 800);
                });
        } ,
    }
};
