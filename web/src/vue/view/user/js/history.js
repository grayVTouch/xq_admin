import mixin from './mixin.js';

const history = {
    page: 1 ,
    limit: TopContext.limit ,
    data: [] ,
    total: 0 ,
};

export default {
    name: "history" ,

    data () {
        return {
            // 表单搜索
            search: {
                relation_type: '' ,
                value: '' ,
            } ,

            relationType: {
                image_project: '图片专题' ,
                // video_subject: '视图专题' ,
                // article_subject: '文章专题' ,
                // bbs_subject: '论坛帖子' ,
            } ,

            history: G.copy(history , true),

            dom: {} ,
            val: {
                pending: {} ,
                fixed: false ,
            } ,
        };
    } ,

    mounted () {
        this.$emit('focus-menu' , 'history');
        this.initDom();
        this.initEvent();
        this.getHistory();
    } ,

    mixins: [
        mixin
    ] ,

    methods: {
        initDom () {
            this.dom.win = G(window);
            this.dom.filter = G(this.$refs.filter);
        } ,

        scrollEvent () {
            const scrollTop = this.dom.filter.getWindowOffsetVal('top');
            this.val.fixed = scrollTop < TopContext.val.fixedTop;
        } ,

        initEvent () {
            this.dom.win.on('scroll' , this.scrollEvent.bind(this));
        } ,

        getHistory () {
            this.pending('getHistory' , true);
            Api.user.history({
                limit: this.history.limit ,
                page: this.history.page ,
                ...this.search ,
            }.then((res) => {
                this.pending('getHistory' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.getHistory();
                    });
                    return ;
                }

                //
                data.data.forEach((group) => {
                    group.data.forEach((v) => {
                        v.relation = v.relation ? v.relation : {};
                        switch (v.relation_type)
                        {
                            case 'image_project':
                                this.handleImageProject(v.relation);
                                break;
                        }
                    });
                });
                this.history.total = data.total;
                this.history.page = data.current_page;
                this.history.data = data.data;
            });
        } ,

        destroyHistory (history) {
            const pending = 'destroyHistory_' + history.id;
            if (this.pending(pending)) {
                return ;
            }
            this.pending(pending , true);
            Api.user.destroyHistory([history.id].then((res) => {
                this.pending(pending , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandleAtUserChildren(msg , data , code , () => {
                        this.destroyHistory(history);
                    });
                    return ;
                }
                this.getHistory();
            })

        } ,

        searchHistory () {
            this.history.page = 1;
            this.getHistory();
        } ,

        toPage (page) {
            this.history.page = page;
            this.getHistory();
        } ,
    } ,
}
