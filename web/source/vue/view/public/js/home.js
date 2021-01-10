import nav from './nav.js';
import position from './position.js';

const loginForm = {
    username: '' ,
    password: '' ,
};
const forgetForm = {
    email: '' ,
    email_code: '' ,
    password: '' ,
    confirm_password: '' ,
};

const registerForm = {
    email: '' ,
    nickname: '' ,
    password: '' ,
    confirm_password: '' ,
    email_code: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const loginError = {
    username: '' ,
    password: '' ,
};

const forgetError = {
    email: '' ,
    password: '' ,
    email_code: '' ,
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

const collectionGroup = {
    count: 0 ,
    collections: []

};

export default {
    name: "home" ,

    mixins: [
        position ,
    ] ,

    data () {
        return {
            val: {
                fixed: false,
                navTypeList: false,
                mime: {
                    key: 'image_subject',
                    value: '图片专题',
                },
                toTop: false,

                // 用户登录、注册表单相关字段
                loginError: {...loginError},
                registerError: {...registerError},
                forgetError: {...forgetError},
                loginMessage: {...loginMessage},
                registerMessage: {...registerMessage},
                forgetMessage: {...forgetMessage},

                showPasswordForLogin: false,

                showPasswordForRegister: false,
                showConfirmPasswordForRegister: false,

                showPasswordForForget: false,
                showConfirmPasswordForForget: false,

                pending: {
                    userLogin: false,
                    userRegister: false,
                    updateUserPassword: false,
                    sendEmailCodeForPassword: false,
                    sendEmailCodeForRegister: false,
                },
                focus: {
                    usernameForLogin: false,
                    passwordForLogin: false,

                    emailForRegister: false,
                    passwordForRegister: false,
                    confirmPasswordForRegister: false,
                    captchaCodeForRegister: false,
                    emailCodeForRegister: false,

                    emailForForget: false,
                    passwordForForget: false,
                    confirmPasswordForForget: false,
                    emailCodeForForget: false,
                },
                captchaForRegister: {},
                /**
                 * login
                 * forget
                 * register
                 */
                userFormType: 'login',
                // 相关步骤
                step: {
                    // 1. email - 发送电子邮箱
                    // 2. password - 修改密码
                    password: 'email',
                },
                timer: {
                    password: 0,
                    register: 0,
                },
            },
            dom: {},
            ins: {},
            search: {
                /**
                 * image
                 * video
                 * article
                 */
                mime: 'image',
            },
            mimeRange: {
                image_subject: '图片专题',
                video_subject: '视频专题',
                video: '视频',
                image: '图片',
                // article: '资讯' ,
                // bbs: '论坛' ,
            },
            // 导航菜单
            nav ,
            // 是否缓存路由
            keepalive: true,
            count: 0,
            loginForm: {...loginForm},
            registerForm: {...registerForm},
            forgetForm: {...forgetForm},
            histories: [],
            favorites: {
                total_collection_group: 0,
                collectionGroups: [],
                collection_group: {...collectionGroup},
            },
            // 用户回调
            userFormCallback: {...userFormCallback},

            // 模块列表
            modules: [],
        };
    } ,

    // beforeRouteEnter (to , from , next) {
    //     if (from.path === '/welcome') {
    //         next(() => {
    //             window.history.go(0);
    //         });
    //         return ;
    //     }
    //     next();
    // } ,

    beforeRouteUpdate (to , from , next) {
        this.initPosition(to.fullPath);
        const position = this.$store.state.position;
        // 菜单失去焦点
        this.ins.nav.blur();
        // 菜单获取焦点
        if (position.length > 0) {
            const first = position[0];
            const last  = position[position.length - 1];
            const ids   = first.route === last.route ?  [first.route] : [first.route , last.route];
            this.ins.nav.focusByIds(ids);
        }
        next();
    } ,

    mounted () {
        this.initDom();
        this.initEvent();
        this.initStyle();
        this.initToTop();

        // 数据获取
        this.getNavs();
        this.getModules();

        if (G.s.exists('token')) {
            // 用户如果已经登录，则获取用户信息
            this.userInfo();
        }
    } ,

    computed: {

    } ,

    methods: {

        getModules () {
            this.pending('getModules' , true);
            Api.module.all((msg , data , code) => {
                this.pending('getModules' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.modules = data;
            });
        } ,

        switchModule (module) {
            this.hideListForModuleSwitch();
            G.s.json('module' , module);
            window.history.go(0);
        } ,

        userInfo (callback) {
            this.pending('userInfo' , true);
            Api.user.info((msg , data , code) => {
                this.pending('userInfo' , false);
                if (code !== TopContext.code.Success) {
                    G.invoke(callback , null , false);
                    if (code === TopContext.code.AuthFailed) {
                        return ;
                    }
                    this.message('error' , msg);
                    return ;
                }
                G.s.json('user' , data);
                this.initUser();
                G.invoke(callback , null , true);
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
                case 'image_subject':
                    this.push('/image_subject/search');
                    break;
                case 'video_subject':
                    this.push('/video_subject/search');
                    break;
            }
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
            this.dom.listForModuleSwitch = G(this.$refs['list-for-module-switch']);
        } ,

        initNav () {
            this.initPosition(this.$route.fullPath);

            const self      = this;
            const position  = this.$store.state.position;
            const first     = position[0];
            const last      = position[position.length - 1];
            const ids       = position.length > 0 ?
                (
                    first.route === last.route ?
                    [first.route] :
                    [first.route , last.route]
                ) :
                [];
            // console.log(this.$route.fullPath , position);
            this.ins.nav    = new Nav(this.dom.navMenu.get(0) , {
                click (id) {
                    self.link(self.genUrl(id) , '_self');
                } ,
                // 是否选中项
                focus: true ,
                // 是否选中顶级项
                topFocus: true ,
                // 初始选中的项
                ids ,
            });

            // 分类需要修改（区分不同的事物主体，而不再是一套分类适用于所有事物）
            // 比如，视频、图片、视频专题、图片专题等，他们不允许共用一套分类！
            // 需要为他们分别设计单独的分类体系
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

        getNavs () {
            Api.home.nav((msg , data , code) => {
                if (code !== TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                let appendImageSubjectNav = [];
                let appendVideoSubjectNav = [];
                let maxId = this.getMaxIdAtPosition();

                const imageSubjectNav = this.findCurrentByRoute('/image_subject');
                const videoSubjectNav = this.findCurrentByRoute('/video_subject');
                // id / p_id 转换成客户端体系
                const mappings = [];
                const findInMappingsByOldId = (oldId) => {
                    for (let i = 0; i < mappings.length; ++i)
                    {
                        const cur = mappings[i];
                        if (cur.oldId === oldId) {
                            return cur;
                        }
                    }
                    throw new Error('未找到 id = ' + oldId + '的映射项');
                };

                data.forEach((v) => {
                    const mapping = {
                        oldId: v.id ,
                        newId: ++maxId ,
                        oldParentId: v.p_id ,
                        newParentId: undefined ,
                    };
                    switch (v.type)
                    {
                        case 'image_project':
                            mapping.newParentId = v.p_id === 0 ? imageSubjectNav.id : findInMappingsByOldId(v.p_id).newId;
                            appendImageSubjectNav.push({
                                id: mapping.newId ,
                                name: v.name ,
                                parentId: mapping.newParentId ,
                                route: '/image_subject/search?category_id=' + v.value ,
                                hidden: false ,
                                isBuiltIn: false ,
                            });
                            break;
                        case 'video_project':
                            mapping.newParentId = v.p_id === 0 ? videoSubjectNav.id : findInMappingsByOldId(v.p_id).newId;
                            appendVideoSubjectNav.push({
                                id: mapping.newId ,
                                name: v.name ,
                                parentId: mapping.newParentId ,
                                route: '/video_subject/search?category_id=' + v.value ,
                                hidden: false ,
                                isBuiltIn: false ,
                            });
                            break;
                        default:
                            throw new Error('不支持的动态菜单类型');
                    }
                    mappings.push(mapping);
                });
                const field = {id: 'id' , p_id: 'parentId'};

                appendImageSubjectNav = G.tree.childrens(imageSubjectNav.id , appendImageSubjectNav , field , false , true);
                appendVideoSubjectNav = G.tree.childrens(videoSubjectNav.id , appendVideoSubjectNav , field , false , true);

                // 请使用下面这种方式来触发节点更新
                appendImageSubjectNav.forEach((v) => {
                    imageSubjectNav.children.push(v);
                });

                appendVideoSubjectNav.forEach((v) => {
                    videoSubjectNav.children.push(v);
                });

                // 初始化获取获取当前路由所在具体位置
                this.$nextTick(() => {
                    this.initNav();
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
            window.clearTimeout(this.val.navTypeListTimer);
            this.dom.navTypeList.removeClass('hide');
            this.dom.navTypeList.startTransition('show');
        } ,

        hideNavTypeList () {
            this.dom.navTypeList.removeClass('show');
            this.dom.navTypeList.addClass('hide');
            // this.dom.navTypeList.endTransition('show');
            // this.val.navTypeListTimer = window.setTimeout(() => {
            //     this.dom.navTypeList.addClass('hide');
            // } , 300);
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
            // this.dom.win.on('click' , this.hideNavTypeList.bind(this));

            this.dom.win.on('click' , this.hideHistoryCtrl.bind(this));
            this.dom.win.on('click' , this.hideUserCtrl.bind(this));
            this.dom.win.on('click' , this.hideFavoritesCtrl.bind(this));
            this.dom.root.on('scroll' , this.fixedHeader.bind(this));

            this.dom.win.on('scroll' , this.fixedHeader.bind(this));
            this.dom.toTop.on('click' , this.toTopEvent.bind(this));
            this.dom.win.on('scroll' , this.initToTop.bind(this));
        } ,

        showListForModuleSwitch () {
            this.dom.listForModuleSwitch.removeClass('hide');
            this.dom.listForModuleSwitch.startTransition('show');
        } ,

        hideListForModuleSwitch () {
            this.dom.listForModuleSwitch.addClass('hide');
            this.dom.listForModuleSwitch.removeClass('show');
        } ,

        // 获取历史记录
        getHistories () {
            this.pending('getHistories' , true);
            Api.user.lessHistory({
                limit: TopContext.limit
            } , (msg , data , code) => {
                this.pending('getHistories' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code);
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
            this.hideFavoritesCtrl();
            this.hideUserCtrl();
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
            this.hideFavoritesCtrl();
            this.hideHistoryCtrl();
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
            this.hideUserCtrl();
            this.hideHistoryCtrl();
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
            } , (msg , data , code) => {
                this.pending('getCollectionGroupWithCollection' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code);
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
                this.favorites.collection_group = data.collection_groups.length > 0 ? {...data.collection_groups[0]} : {...collectionGroup};
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
            if (G.isFunction(callback)) {
                this.userFormCallback[type].push(callback);
            }
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
                this.message('info' , '服务正在处理...请耐心等待');
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
            Api.misc.captcha((msg , data , code) => {
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
                this.message('info' , '请求中...请耐心等待');
                return ;
            }
            this.pending('userLogin' , true);
            Api.user.login(this.loginForm , (msg , data , code) => {
                this.resetLoginError();
                this.resetLoginMessage();
                if (code !== TopContext.code.Success) {
                    this.pending('userLogin' , false);
                    if (code !== TopContext.code.FormValidateFail) {
                        this.message('error' , msg);
                    } else {
                        this.val.loginError = {...data};
                    }
                    return ;
                }
                G.s.set('token' , data);
                // console.log(G.jsonEncode(this.userFormCallback.login) , this.userFormCallback.login.length);
                if (this.userFormCallback.login.length < 1) {
                    this.loginMessage('登录成功，页面刷新中...' , 'run-green');
                    window.setTimeout(this.reload.bind(this) , 1000);
                    return ;
                }
                this.loginMessage('登录成功，获取用户信息中...' , 'run-green');
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

        userRegister () {
            if (this.pending('userRegister')) {
                this.message('info' , '请求中...请耐心等待');
                return ;
            }
            this.pending('userRegister' , true);
            Api.user.register(this.registerForm , (msg , data , code) => {
                this.resetRegisterError();
                this.resetRegisterMessage();
                if (code !== TopContext.code.Success) {
                    this.pending('userRegister' , false);
                    this.captchaForRegister();
                    if (G.isString(data)) {
                        this.message('error' , msg);
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

        sendEmailCodeForPassword () {
            if (this.val.timer.password > 0) {
                return ;
            }
            // 发送验证码
            this.pending('sendEmailCodeForPassword' , true);
            Api.misc.sendEmailCodeForPassword(this.forgetForm.email , (msg , data , code) => {
                this.pending('sendEmailCodeForPassword' , false);
                if (code !== TopContext.code.Success) {
                    if (code !== TopContext.code.FormValidateFail) {
                        this.message('error' , msg);
                        return ;
                    }
                    this.val.forgetError = {...data};
                    return ;
                }
                // 密码
                this.val.step.password = 'password';
                this.forgetMessage('邮箱验证码发送成功' , 'run-green');
                window.setTimeout(() => {
                    this.resetForgetMessage();
                } , 3000);
                G.timeCount(60 , 1 , (v) => {
                    this.val.timer.password = v;
                });
            });
        } ,

        sendEmailCodeForRegister () {
            if (this.val.timer.register > 0) {
                return ;
            }
            // 发送验证码
            this.pending('sendEmailCodeForRegister' , true);
            Api.misc.sendEmailCodeForRegister(this.registerForm.email , (msg , data , code) => {
                this.pending('sendEmailCodeForRegister' , false);
                if (code !== TopContext.code.Success) {
                    if (code !== TopContext.code.FormValidateFail) {
                        this.message('error' , msg);
                        return ;
                    }
                    this.val.registerError = {...data};
                    return ;
                }
                // 密码
                this.forgetMessage('邮箱验证码发送成功' , 'run-green');
                window.setTimeout(() => {
                    this.resetRegisterMessage();
                } , 3000);
                G.timeCount(60 , 1 , (v) => {
                    this.val.timer.register = v;
                });
            });
        } ,

        updateUserPassword () {
            if (this.pending('updateUserPassword')) {
                this.message('info' , '请求中...请耐心等待');
                return ;
            }
            this.pending('updateUserPassword' , true);
            Api.user.updatePassword(this.forgetForm , (msg , data , code) => {
                this.resetForgetError();
                this.resetForgetMessage();
                if (code !== TopContext.code.Success) {
                    this.pending('updateUserPassword' , false);
                    if (G.isString(data)) {
                        this.message('error' , msg);
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
                    this.val.password = 'email';
                    this.pending('updateUserPassword' , false);
                    this.resetForgetMessage();
                    this.resetForgetForm();
                    this.showUserForm('login');
                } , 1000);
            });
        } ,
    } ,
}
