
import store from '../../vue/vuex/index.js';

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
                request: {} ,
            } ,
        };
    } ,

    methods: {

        isValid (val) {
            return G.isValid(val) && !G.isEmptyString(val);
        } ,

        push (...args) {
            this.$router.push.apply(this.$router , args);
        } ,

        notice (title , desc) {
            return this.$Notice.open({
                title ,
                desc ,
            });
        } ,

        state (key) {
            return this.$store.state[key];
        } ,

        modal (action , title , content = '' , onOk , merge = {}) {
            return this.$Modal[action]({
                title ,
                content ,
                onOk ,
                ...merge ,
            });
        } ,

        message (action , content = '' , merge = {}) {
            return this.$Message[action]({
                background: true ,
                content ,
                ...merge ,
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

        select (key , val) {
            if (!G.isValid(val)) {
                return this.val.select[key];
            }
            this.val.select[key] = val;
            this.val.select = {...this.val.select , ...{[key]: val}};
        } ,

        errorHandle (msg , data , code) {
            if (code !== TopContext.code.FormValidateFail) {
                this.error();
                this.message('error' , msg);
                return ;
            }
            this.message('error' , G.isEmptyString(msg) ? '表单错误，请检查' : msg);
            this.error(data);
        } ,

        successHandle (callback) {
            return this.modal('confirm' , '操作成功' , '' , null , {
                okText: '继续' ,
                cancelText: '取消' ,
                onOk () {
                    G.invoke(callback , null , true);
                } ,
                onCancel () {
                    G.invoke(callback , null , false);
                } ,
            });
        } ,

        confirmModal (title , callback) {
            return this.modal('confirm' , title , '' , null , {
                onOk () {
                    G.invoke(callback , null , true);
                } ,
                onCancel () {
                    G.invoke(callback , null , false);
                } ,
            });
        } ,

        request (name , val) {
            if (!G.isValid(val)) {
                return this.val.request[name];
            }
            this.val.request = {...this.val.request , ...{[name]: val}};
        } ,

        fileApi (resize = false) {
            return TopContext.fileApi;
        } ,

        imageApi (resize = false) {
            return TopContext.uploadImageApi  + (resize ? '?w=' + TopContext.val.imageW : '');
        } ,

        thumbApi (resize = true) {
            return TopContext.uploadImageApi + (resize ? '?w=' + TopContext.val.thumbW : '');
        } ,

        videoApi () {
            return TopContext.uploadVideoApi;
        } ,

        subtitleApi () {
            return TopContext.uploadSubtitleApi;
        } ,

        officeApi () {
            return TopContext.uploadOfficeApi;
        } ,

        handleImageSubject () {

        } ,

        handleVideo () {

        } ,

        getUsername (username , nickname) {
            return username ? username : (nickname ? nickname : '');
        } ,


    }
});
