
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
                console.log('导航地址：' , location , this.$route.path);
                if (location === this.$route.path) {
                    return ;
                }
            } else {
                console.log('导航地址：' , location.path , this.$route.path);
                if (location.name === this.$route.name || location.path === this.$route.path) {
                    return ;
                }
            }
            this.$router.push.apply(this.$router , [location , onComplete , onAbort]);
        } ,

        state (key) {
            return this.$store.state[key];
        } ,

        message (msg , option = {}) {
            console.log({
                closeBtn: true ,
                ...option
            });

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
        errorHandle (data , code , callback) {
            if (code === TopContext.code.AuthFailed) {
                this.message('您尚未登录，请登录后操作？' , {
                    closeBtn: false ,
                    btn: [
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
            this.message(data);
        } ,

        errorHandleAtHomeChildren (data , code) {
            const self = this;
            if (code === TopContext.code.AuthFailed) {
                this.message('您尚未登录，请登录后操作？' , {
                    closeBtn: false ,
                    btn: [
                        {
                            name: '确定' ,
                            callback () {
                                self.showUserForm('login');
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
            this.message(data);
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
    }
});
