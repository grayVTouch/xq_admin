import mixin from './mixin.js';

const history = {
    page: 1 ,
    size: TopContext.size ,
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
                video_project: '视频专题' ,
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
            Api.user
                .history({
                    size: this.history.size ,
                    page: this.history.page ,
                    ...this.search ,
                })
                .then((res) => {
                    this.pending('getHistory' , false);
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtUserChildren(res.message , res.code , () => {
                            this.getHistory();
                        });
                        return ;
                    }
                    const data = res.data;
                    data.data.forEach((group) => {
                        group.data.forEach((v) => {
                            v.relation = v.relation ? v.relation : {};
                            switch (v.relation_type)
                            {
                                case 'image_project':
                                    this.handleImageProject(v.relation);
                                    break;
                                case 'video_project':
                                    v.relation.user = v.relation.user ? v.relation.user : {};
                                    v.relation.user_play_record = v.relation.user_play_record ? v.relation.user_play_record : {};
                                    v.relation.user_play_record.video = v.relation.user_play_record.video ? v.relation.user_play_record.video : {};
                                    break;
                                default:
                            }
                        });
                    });
                    this.history.total = data.total;
                    this.history.page = data.current_page;
                    this.history.data = data.data;
                })
                .finally(() => {

                });
        } ,

        destroyHistory (history) {
            const pendingKey = 'destroyHistory_' + history.id;
            if (this.pending(pendingKey)) {
                return ;
            }
            this.pending(pendingKey , true);
            Api.user
                .destroyHistory(null , {
                    history_ids: G.jsonEncode([history.id])
                })
                .then((res) => {
                    this.pending(pendingKey , false);
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtUserChildren(res.message , res.code , () => {
                            this.destroyHistory(history);
                        });
                        return ;
                    }
                    this.getHistory();
                })
                .finally(() => {

                });

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
