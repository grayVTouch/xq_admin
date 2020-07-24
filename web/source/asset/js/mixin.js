
import store from '../../vue/vuex';

/**
 * ****************
 * 全局混入
 * ****************
 */
Vue.mixin({

    store ,

    data () {
        return {
            val: {
                pending: {} ,
                error: {} ,
                select: {} ,
            } ,
        };
    } ,

    methods: {

        isValid (val) {
            return G.isValid(val);
        } ,

        push (location , onComplete , onAbort) {
            if (G.isString(location)) {
                if (location === this.$route.path) {
                    return ;
                }
            } else {
                if (
                    (location.name && location.name === this.$route.name) ||
                    (location.path && location.path === this.$route.path)
                ) {
                    return ;
                }
            }
            this.$router.push.apply(this.$router , [location , onComplete , onAbort]);
        } ,

        state (key) {
            return this.$store.state[key];
        } ,

        message (msg , option = {}) {
            return Prompt.alert(msg , {
                closeBtn: true ,
                ...option
            });
        } ,

        link (url , type = '_blank') {
            window.open(url , type);
        } ,

        pending (key , val) {
            if (!G.isValid(val)) {
                return this.val.pending[key];
            }
            this.val.pending[key] = val;
            this.val.pending = {...this.val.pending , ...{[key]: val}};
        } ,

        error (error = {} , clear = true) {
            if (clear) {
                this.val.error = {...error};
            }
            this.val.error = {...this.val.error , ...error};
        } ,

        _val (key , val) {
            if (!G.isValid(val)) {
                return this.val[key];
            }
            this.val = {...this.val , ...{[key]: val}};
        } ,

        errorHandle (msg , data , code , callback) {
            if (code === TopContext.code.AuthFailed) {
                this.message('您尚未登录，请登录后操作？' , {
                    // closeBtn: false ,
                    action: [
                        {
                            name: '确定' ,
                            callback () {
                                callback();
                                this.hide();
                            } ,
                        } ,
                        {
                            name: '取消' ,
                            callback () {
                                this.hide();
                            } ,
                        } ,
                    ] ,
                });
                return ;
            }
            if (code !== TopContext.code.FormValidateFail) {
                this.message(msg);
                return ;
            }
            if (!G.isArray(data)) {
                this.message(msg);
                return ;
            }
            this.message('表单发生错误，请检查');
            this.error(data);
        } ,

        errorHandleAtUserChildren (msg , data , code , loggedCallback) {
            var self = this;
            this.errorHandle(msg , data , code , () => {
                self.$parent.$parent.showUserForm('login' , loggedCallback);
            });
        } ,
        errorHandleAtHomeChildren (msg , data , code , loggedCallback) {
            this.errorHandle(msg , data , code , () => {
                this.$parent.showUserForm('login' , loggedCallback);
            });
        } ,

        successHandle (callback) {
            return this.message('操作成功' , {

                // 点击确认按钮回调
                confirm () {
                    G.invoke(callback , null , true);
                } ,

                // 点击取消按钮回调
                cancel () {
                    G.invoke(callback , null , false);
                } ,
            });
        } ,

        dispatch (action , payload) {
            this.$store.dispatch(action , payload);
        } ,

        handleImageSubject (data) {
            data.user    = data.user ? data.user : {};
            data.subject = data.subject ? data.subject : {};
            data.images  = data.images ? data.images : [];
            data.tags    = data.tags ? data.tags : [];
            data.module  = data.module ? data.module : [];
        } ,

        key () {
            return G.randomArr('64' , 'mixed' , true);
        } ,

        getUsername (username , nickname) {
            return nickname ? nickname : username;
        } ,

        genUrl (route) {
            return '#' + route;
        } ,

        reload () {
            window.history.go(0);
        } ,
    } ,
});
