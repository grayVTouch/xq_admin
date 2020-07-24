export default {
    name: "index" ,
    data () {
        return {
            dom:{} ,
            ins: {} ,
            val: {
                pending: {} ,
                nav: '' ,
                // 模式：编辑 - edit 和 预览 - preview
                mode: 'preview' ,
            } ,
            user: {} ,
            form: {
                nickname: '' ,
                description: '' ,
            } ,
        };
    } ,
    mounted () {
        this.initDom();
        this.initIns();
        this.init();
        this.getUserById(this.id);
    } ,

    props: ['id'] ,

    beforeRouteUpdate (to , from , next) {
        if (to.params.id !== from.params.id) {
            // 更新用户信息
            this.getUserById(to.params.id);
        }
        next();
    } ,

    methods: {

        init () {
            const nav = this.$route.path.replace(`/channel/${this.id}/` , '');
            this.switchNavMappingItemById(nav);
        } ,

        getUserById (userId) {
            this.pending('getData' , true);
            Api.user.show(userId , (msg , data , code) => {
                this.pending('getData' , false);
                if (code !== TopContext.code.Success) {
                    this.errorHandleAtHomeChildren(msg , data , code);
                    return ;
                }
                this.user = {...data};

                this.form.nickname = this.getUsername(data.username , data.nickname);
                this.form.description = data.description;
            });
        } ,

        initDom () {
            this.dom.navMappingItem = G(this.$refs['nav-mapping-item']);
            this.dom.navs = G(this.$refs.navs);
            this.dom.lineInNav = G(this.$refs['line-in-nav']);
        } ,

        initIns () {
            // this.ins.slideSwitch = new SlideSwitch(this.dom.navMappingItem.get(0) , {
            //     id: this.val.nav ,
            // });
        } ,

        moveLineInNavByIndex (index) {
            const w = this.dom.lineInNav.width();
            const translateX = (index - 1) * w;
            this.dom.lineInNav.css({
                translateX: translateX + 'px' ,
            });
        } ,

        switchNavMappingItemById (id) {
            if (this.val.nav === id) {
                return ;
            }
            this.pending('switch' , true);
            const nav = G(this.$refs['nav-' + id]);
            const prevSiblliings = nav.prevSiblings();
            this.moveLineInNavByIndex(prevSiblliings.length + 1);
            this._val('nav' , id);
            this.push(`/channel/${this.id}/${id}`);
        } ,

        onCopy () {
            Tip.info('链接已经粘贴到剪贴板');
        } ,

        changeThumb (channelThumb) {
            this.pending('changeThumb' , true);
            Api.user.localUpdate(this.user.id , {
                channel_thumb: channelThumb
            } , (msg , data , code) => {
                this.pending('changeThumb' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.user = {...data};
            });
        } ,

        changeAvatar (avatar) {
            this.pending('changeAvatar' , true);
            Api.user.localUpdate(this.user.id , {
                avatar: avatar
            } , (msg , data , code) => {
                this.pending('changeAvatar' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.user = {...data};
            });
        } ,

        changeNickname () {
            this.pending('changeNickname' , true);
            Api.user.localUpdate(this.user.id , {
                nickname: this.form.nickname
            } , (msg , data , code) => {
                this.pending('changeNickname' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.user = {...data};
            });
        } ,

        changeDescription () {
            this.pending('changeDescription' , true);
            Api.user.localUpdate(this.user.id , {
                description: this.form.description
            } , (msg , data , code) => {
                this.pending('changeDescription' , false);
                if (code !== TopContext.code.Success) {
                    this.message(msg);
                    return ;
                }
                this.user = {...data};
            });
        } ,
    } ,
}