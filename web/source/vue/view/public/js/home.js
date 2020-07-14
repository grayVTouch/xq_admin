const loginForm = {
    username: '' ,
    password: '' ,
};
const forgetForm = {
    username: '' ,
    password: '' ,
    confirm_password: '' ,
};

const registerForm = {
    username: '' ,
    password: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const loginError = {
    username: '' ,
    password: '' ,
};

const forgetError = {
    username: '' ,
    password: '' ,
    confirm_password: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const registerError = {
    username: '' ,
    password: '' ,
    confirm_password: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const loginMessage = {
    class: '' ,
    text: '' ,
};

const registerMessage = {...loginMessage};
const forgetMessage = {...loginMessage};

const userFormCallback = {
    login: [] ,
    register: [] ,
    forget: [] ,
};

export default {
    name: "home" ,

    data () {
        return {
            val: {
                fixed: false ,
                navTypeList: false ,
                mime: {
                    key: 'image' ,
                    value: '图片' ,
                } ,
                toTop: false ,

                // 用户登录、注册表单相关字段
                loginError: {...loginError} ,
                registerError: {...registerError} ,
                forgetError: {...forgetError} ,
                loginMessage: {...loginMessage} ,
                registerMessage: {...registerMessage} ,
                forgetMessage: {...forgetMessage} ,

                showPasswordForLogin: false ,

                showPasswordForRegister: false ,
                showConfirmPasswordForRegister: false ,

                showPasswordForForget: false ,
                showConfirmPasswordForForget: false ,

                pending: {
                    userLogin: false ,
                    userRegister: false ,
                    updateUserPassword: false ,
                } ,
                focus: {
                    usernameForLogin: false ,
                    passwordForLogin: false ,

                    usernameForRegister: false ,
                    passwordForRegister: false ,
                    confirmPasswordForRegister: false ,
                    captchaCodeForRegister: false ,

                    usernameForForget: false ,
                    passwordForForget: false ,
                    confirmPasswordForForget: false ,
                } ,
                captchaForRegister: {} ,
                /**
                 * login
                 * forget
                 * register
                 */
                userFormType: 'login' ,
            } ,
            dom: {} ,
            ins: {} ,
            search: {
                /**
                 * image
                 * video
                 * article
                 */
                mime: 'image' ,
            } ,
            mimeRange: {
                image: '图片' ,
                // video: '视频' ,
                // article: '资讯' ,
                // bbs: '论坛' ,
            } ,
            nav: [] ,
            keepalive: true ,
            count: 0 ,
            loginForm: {...loginForm} ,
            registerForm: {...registerForm} ,
            forgetForm: {...forgetForm} ,
            histories:[] ,
            favorites:{
                total_collection_group: 0 ,
                collectionGroups: [] ,
                collection_group: {
                    count: 0 ,
                    collections: [] ,
                } ,
            } ,
            // 用户回调
            userFormCallback: {...userFormCallback} ,
        };
    } ,

    beforeRouteEnter (to , from , next) {
        if (from.path === '/welcome') {
            next(() => {
                window.history.go(0);
            });
            return ;
        }
        next();
    } ,

    beforeRouteUpdate (to , from , next) {
        this.initPosition(to.path);
        // 找到当前路由所在位置
        const position = this.getNavByPath(to.fullPath);
        // const position = this.getNavByPath(to.path);
        if (position.length > 0) {
            // console.log(to.path , position , position[position.length - 1].link);
            this.ins.nav.focusById(position[position.length - 1].link);
        } else {
            // 没有选中项
            this.ins.nav.blur();
        }
        next();
    } ,

    mounted () {
        this.initDom();
        this.initNavData();
        this.initEvent();
        this.initStyle();
        this.initToTop();

        if (G.s.exists('token')) {
            // 用户如果已经登录，则获取用户信息
            this.userInfo();
        }
    } ,

    computed: {

    } ,

    methods: {

        // 获取历史记录
        getHistories () {
            this.pending('getHistories' , true);
            Api.user.lessHistory({
                limit: TopContext.limit
            } , (data , code) => {
                this.pending('getHistories' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(data , code);
                    return ;
                }
                data.forEach((v) => {
                    v.relation = v.relation ? v.relation : {};
                    v.relation.user = v.relation.user ? v.relation.user : {};
                    v.relation.module = v.relation.module ? v.relation.module : {};
                });
                this.histories = data;
            });
        } ,


        showHistoryCtrl () {
            this.dom.groupsForHistory = G(this.$refs['groups-for-history']);
            this.dom.groupsForHistory.removeClass('hide');
            this.dom.groupsForHistory.startTransition('show');
            if (this.histories.length <= 0) {
                this.getHistories();
            }
        } ,

        hideHistoryCtrl () {
            this.dom.groupsForHistory = G(this.$refs['groups-for-history']);
            this.dom.groupsForHistory.endTransition('show' , () => {
                this.dom.groupsForHistory.addClass('hide');
            });
        } ,

        showUserCtrl () {
            this.dom.infoForUser = G(this.$refs['info-for-user']);
            this.dom.infoForUser.removeClass('hide');
            this.dom.infoForUser.startTransition('show');
        } ,

        hideUserCtrl () {
            this.dom.infoForUser = G(this.$refs['info-for-user']);
            this.dom.infoForUser.endTransition('show' , () => {
                this.dom.infoForUser.addClass('hide');
            });
        } ,

        // 获取收藏夹内容
        showFavoritesCtrl () {
            this.dom.collection = G(this.$refs['collection']);
            this.dom.collection.removeClass('hide');
            this.dom.collection.startTransition('show');
            if (this.favorites.collectionGroups.length <= 0) {
                this.getCollectionGroupWithCollection();
            }
        } ,

        hideFavoritesCtrl () {
            this.dom.collection = G(this.$refs['collection']);
            this.dom.collection.endTransition('show' , () => {
                this.dom.collection.addClass('hide');
            });
        } ,

        getCollectionGroupWithCollection () {
            this.pending('getCollectionGroupWithCollection' , true);
            Api.user.lessCollectionGroupWithCollection({
                // limit: TopContext.limit
            } , (data , code) => {
                this.pending('getCollectionGroupWithCollection' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(data , code);
                    return ;
                }
                data.collection_groups = data.collection_groups ? data.collection_groups : [];
                data.collection_groups.forEach((v) => {
                    v.user = v.user ? v.user : {};
                    v.module = v.module ? v.module : {};
                    v.collections = v.collections ? v.collections : [];
                    v.collections.forEach((v1) => {
                        v1.relation = v1.relation ? v1.relation : {};
                        v1.relation.user = v1.relation.user ? v1.relation.user : {};
                        v1.relation.module = v1.relation.module ? v1.relation.module : {};
                        v1.relation.collection_group = v1.relation.collection_group ? v1.relation.collection_group : {};
                        v1.relation.relation = v1.relation.relation ? v1.relation.relation : {};
                    });
                });
                this.favorites.total_collection_group = data.total_collection_group;
                this.favorites.collectionGroups = data.collection_groups;
                this.favorites.collection_group = data.collection_groups.length > 0 ? data.collection_groups[0] : {
                    collections: []
                };
            });
        } ,

        logout () {
            G.s.del('token');
            G.s.del('user');
            window.history.go(0);
        } ,

        // 初始化用户信息
        initUser () {
            const user = G.s.json('user');
            this.dispatch('user' , user);
        } ,

        showUserForm (type , callback) {
            this._val('userFormType' ,type);
            switch (type)
            {
                case 'login':
                    break;
                case 'register':
                    this.captchaForRegister();
                    break;
                case 'forget':
                    break;
                default:
                    this.message('不支持的类型');
            }
            this.dom.userForm.removeClass('hide');
            this.dom.userForm.startTransition('show');
            this.userFormCallback[type].push(callback);
        } ,

        switchUserForm (type) {
            this._val('userFormType' ,type);
            switch (type)
            {
                case 'login':
                    break;
                case 'register':
                    this.captchaForRegister();
                    break;
                case 'forget':
                    break;
                default:
                    this.message('不支持的类型');
            }
        } ,

        hideUserForm () {
            if (this.pending('userLogin') || this.pending('userRegister') || this.pending('updateUserPassword')) {
                this.message('服务正在处理...请耐心等待');
                return ;
            }
            this.dom.userForm.endTransition('show' , () => {
                this.dom.userForm.addClass('hide');
            });
            this.userFormCallback = {...userFormCallback};
        } ,

        loginMessage (text = '' , classname = '') {
            this.val.loginMessage.text = text;
            this.val.loginMessage.class = classname;
        } ,

        registerMessage (text = '' , classname = '') {
            this.val.registerMessage.text = text;
            this.val.registerMessage.class = classname;
        } ,

        forgetMessage (text = '' , classname = '') {
            this.val.forgetMessage.text = text;
            this.val.forgetMessage.class = classname;
        } ,

        captchaForRegister () {
            Api.misc.captcha((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.val.registerError.captcha_code = data;
                    return ;
                }
                this.val.captchaForRegister = data;
                this.registerForm.captcha_key = data.key;
            })
        } ,

        focusEvent (e) {
            const tar = G(e.currentTarget);
            const name = tar.data('name');
            this.val.focus[name] = true;
        } ,

        blurEvent (e) {
            const tar = G(e.currentTarget);
            const name = tar.data('name');
            this.val.focus[name] = false;
        } ,

        resetLoginForm () {
            this.loginForm = {...loginForm};
        } ,

        resetForgetForm () {
            this.forgetForm = {...forgetForm};
        } ,

        resetRegisterForm () {
            this.registerForm = {...registerForm};
        } ,



        resetLoginError () {
            this.val.loginError = {...loginError};
        } ,

        resetForgetError () {
            this.val.forgetError = {...forgetError};
        } ,

        resetRegisterError () {
            this.val.registerError = {...registerError};
        } ,

        resetLoginMessage () {
            this.val.loginMessage = {...loginMessage};
        } ,

        resetRegisterMessage () {
            this.val.registerMessage = {...registerMessage};
        } ,

        resetForgetMessage () {
            this.val.forgetMessage = {...forgetMessage};
        } ,

        userLogin () {
            if (this.pending('userLogin')) {
                this.message('请求中...请耐心等待');
                return ;
            }
            this.pending('userLogin' , true);
            Api.user.login(this.loginForm , (data , code) => {
                this.resetLoginError();
                this.resetLoginMessage();
                if (code !== TopContext.code.Success) {
                    this.pending('userLogin' , false);
                    if (G.isString(data)) {
                        this.message(data);
                        return ;
                    }
                    this.val.loginError = {...data};
                    return ;
                }
                this.loginMessage('登录成功，获取用户信息中...' , 'run-green');
                G.s.set('token' , data);
                // 获取用户信息
                this.userInfo((keep) => {
                    this.pending('userLogin' , false);
                    this.resetLoginForm();
                    this.resetLoginMessage();
                    if (!keep) {
                        this.loginMessage('获取用户信息失败，请稍后重试' , 'run-red');
                        return ;
                    }
                    while (this.userFormCallback.login.length > 0)
                    {
                        const callback = this.userFormCallback.login.pop();
                        G.invoke(callback);
                    }
                    this.hideUserForm();
                });
            });
        } ,

        userInfo (callback) {
            this.pending('userInfo' , true);
            Api.user.info((data , code) => {
                this.pending('userInfo' , false);
                if (code !== TopContext.code.Success) {
                    G.invoke(callback , null , false);
                    if (code === TopContext.code.AuthFailed) {
                        return ;
                    }
                    this.message(data);
                    return ;
                }
                G.s.json('user' , data);
                this.initUser();
                G.invoke(callback , null , true);
            });
        } ,

        userRegister () {
            if (this.pending('userRegister')) {
                this.message('请求中...请耐心等待');
                return ;
            }
            this.pending('userRegister' , true);
            Api.user.register(this.registerForm , (data , code) => {
                this.resetRegisterError();
                this.resetRegisterMessage();
                if (code !== TopContext.code.Success) {
                    this.pending('userRegister' , false);
                    this.captchaForRegister();
                    if (G.isString(data)) {
                        this.message(data);
                        return ;
                    }
                    this.val.registerError = {...data};
                    return ;
                }
                this.registerMessage('注册成功，获取用户信息中...' , 'run-green');
                G.s.set('token' , data);
                // 获取用户信息
                this.userInfo((keep) => {
                    this.pending('userRegister' , false);
                    this.resetRegisterForm();
                    this.resetRegisterMessage();
                    if (!keep) {
                        this.registerMessage('获取用户信息失败，请稍后重试' , 'run-red');
                        return ;
                    }

                    this.hideUserForm();
                });
            });
        } ,

        updateUserPassword () {
            if (this.pending('updateUserPassword')) {
                this.message('请求中...请耐心等待');
                return ;
            }
            this.pending('updateUserPassword' , true);
            Api.user.updatePassword(this.forgetForm , (data , code) => {
                this.resetForgetError();
                this.resetForgetMessage();
                if (code !== TopContext.code.Success) {
                    this.pending('updateUserPassword' , false);
                    if (G.isString(data)) {
                        this.message(data);
                        return ;
                    }
                    this.val.forgetError = {...data};
                    return ;
                }
                G.s.del('token');
                G.s.del('user');
                this.dispatch('user' , null);
                this.forgetMessage('修改成功！' , 'run-green');
                window.setTimeout(() => {
                    this.pending('updateUserPassword' , false);
                    this.resetForgetMessage();
                    this.resetForgetForm();
                    this.showUserForm('login');
                } , 1000);
            });
        } ,

        /**
         * *****************
         * 搜索事件
         * *****************
         */
        searchEvent () {
            switch (this.val.mime.key)
            {
                case 'image':
                    this.push('/image_subject/search');
                    break;
            }
        } ,

        initPosition (path) {
            const position = this.getPositionByPath(path);
            this.dispatch('position' , position);
        } ,

        getPositionByPath (path) {
            const res = [];
            let current = this.findCurrentByPath(path);
            while (current !== false)
            {
                res.push(current);
                current = this.findCurrentById(current.p_id);
            }
            res.reverse();
            return res;
        } ,
        
        getNavByPath (path) {
            const position = this.getPositionByPath(path);
            const res = [];
            for (let i = 0; i < position.length; ++i)
            {
                let cur = position[i];
                if (cur.is_menu) {
                    res.push(cur);
                }
            }
            return res;
        } , 

        // 找到当前路由所在菜单项
        findCurrentByPath (path , data) {
            data = data ? data : this.nav;
            let res = false;
            for (let i = 0; i < data.length; ++i)
            {
                const cur = data[i];
                // let route = G.getUri(cur.link);
                let route = cur.value;
                // 搜索1：完整匹配
                if (route === path) {
                    res = cur;
                    break;
                }
                route = route.replace(/\/:\w+(\/?)/g , '/.+?$1');
                route = route.replace(/(\/|\?)/g , '\$1');
                if (new RegExp('^' + route + '$').test(path)) {
                    res = cur;
                    break;
                }
                // 循环匹配
                res = this.findCurrentByPath(path , cur.children);
                if (res !== false) {
                    break;
                }
            }
            return res;
        } ,

        findCurrentById (id , data) {
            data = data ? data : this.nav;
            let res = false;
            for (let i = 0; i < data.length; ++i)
            {
                const cur = data[i];
                if (cur.id === id) {
                    res = cur;
                    break;
                }
                res = this.findCurrentById(id , cur.children);
                if (res !== false) {
                    break;
                }
            }
            return res;
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.body = G(document.body);
            this.dom.root = G(this.$el);
            this.dom.navTypeList = G(this.$refs['nav-type-list']);
            this.dom.navMenu = G(this.$refs['nav-menu']);
            this.dom.body = G(this.$refs.body);
            this.dom.toTop = G(this.$refs['to-top']);
            this.dom.userForm = G(this.$refs.userForm);

            // debugger
        } ,

        initIns () {
            const self = this;
            // const position = this.getNavByPath(this.$route.path);
            const position = this.getNavByPath(this.$route.fullPath);
            this.ins.nav = new Nav(this.dom.navMenu.get(0) , {
                click (id) {
                    // self.push(id);
                } ,
                // 是否选中项
                focus: true ,
                // 是否选中顶级项
                topFocus: true ,
                // 初始选中的项
                ids: position.length > 0 ? [position[position.length - 1].link] : [] ,
            });
        } ,

        initStyle () {
            // this.dom.body.startTransition('show');
        } ,

        switchSearchType (key , value) {
            this.search.mime = key;
            this._val('mime' , {
                key ,
                value ,
            });
            this.hideNavTypeList();
        } ,

        initNavData () {
            Api.home.nav((data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message(data);
                    return ;
                }
                const nav = G.tree.childrens(0 , data , null , false , true);
                this.nav = nav;
                // 初始化获取获取当前路由所在具体位置
                this.initPosition(this.$route.path);
                this.$nextTick(() => {
                    this.initIns();
                });
            });
        } ,

        mimeTypeEvent () {
            if (this.val.navTypeList) {
                this.hideNavTypeList();
            } else {
                this.showNavTypeList();
            }
        } ,

        showNavTypeList () {
            this._val('navTypeList' , true);
            this.dom.navTypeList.removeClass('hide');
            // 显示
            this.dom.navTypeList.startTransition('show');
        } ,

        hideNavTypeList () {
            this._val('navTypeList' , false);
            this.dom.navTypeList.endTransition('show' , () => {
                this.dom.navTypeList.addClass('hide');
            });
        } ,

        // 标题栏置顶
        fixedHeader () {
            const scrollTop = window.pageYOffset;
            this.val.fixed = scrollTop >= 60;
        } ,

        initToTop () {
            const y = window.pageYOffset;
            if (y === 0) {
                this.dom.toTop.endTransition('show' , () => {
                    this.dom.toTop.removeClass('hide');
                });
            } else {
                this.dom.toTop.removeClass('hide');
                this.dom.toTop.startTransition('show');
            }
        } ,

        toTopEvent () {
            G.scrollTo(300 , 'y' , 0 , 0);
        } ,

        initEvent () {
            this.dom.win.on('click' , this.hideNavTypeList.bind(this));

            this.dom.win.on('click' , this.hideHistoryCtrl.bind(this));
            this.dom.win.on('click' , this.hideUserCtrl.bind(this));
            this.dom.win.on('click' , this.hideFavoritesCtrl.bind(this));
            this.dom.root.on('scroll' , this.fixedHeader.bind(this));

            this.dom.win.on('scroll' , this.fixedHeader.bind(this));
            this.dom.toTop.on('click' , this.toTopEvent.bind(this));
            this.dom.win.on('scroll' , this.initToTop.bind(this));
        } ,
    } ,
}