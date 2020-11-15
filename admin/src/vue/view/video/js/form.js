const form = {
    type: 'misc' ,
    user_id: '' ,
    module_id: '' ,
    category_id: '' ,
    video_project_id: '' ,
    view_count: 0  ,
    play_count: 0  ,
    praise_count: 0 ,
    against_count: 0 ,
    weight: 0 ,
    status: 0 ,
    merge_video_subtitle: 0 ,
    index: 1 ,
};

const users = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            minWidth: TopContext.table.id ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '名称' ,
            key: 'username' ,
            minWidth: TopContext.table.name ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '头像' ,
            slot: 'avatar' ,
            minWidth: TopContext.table.image ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '创建时间' ,
            key: 'created_at' ,
            minWidth: TopContext.table.time ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '动作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            center: TopContext.table.alignCenter ,
        } ,
    ] ,
    current: {
        id: 0 ,
        username: 'unknow' ,
    } ,
    limit: TopContext.limit ,
    value: '' ,
    page: 1 ,
    total: 0 ,
};

const videoProjects = {
    data: [],
    field: [
        {
            title: 'id' ,
            key: 'id' ,
            minWidth: TopContext.table.id ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '名称' ,
            key: 'name' ,
            minWidth: TopContext.table.name ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '模块【id】' ,
            slot: 'module_id' ,
            minWidth: TopContext.table.name ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '封面' ,
            slot: 'thumb' ,
            minWidth: TopContext.table.image ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '创建时间' ,
            key: 'created_at' ,
            minWidth: TopContext.table.time ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '动作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            center: TopContext.table.alignCenter ,
        } ,
    ] ,
    current: {
        id: 0 ,
        name: 'unknow' ,
    } ,
    total: 0 ,
    page: 1 ,
    value: '' ,
};

const subtitle = {
    id: 1 ,
    name: '' ,
    src: '' ,
    delete_1: false ,
};

const videos = {
    field: [
        {
            type: 'selection' ,
            width: TopContext.table.checkbox ,
            center: TopContext.table.alignCenter ,
            fixed: 'left' ,
        } ,
        {
            title: 'id' ,
            key: 'id' ,
            minWidth: TopContext.table.id ,
            center: TopContext.table.alignCenter ,
            fixed: 'left' ,
        } ,
        {
            title: '清晰度' ,
            key: 'definition' ,
            minWidth: TopContext.table.name ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '视频' ,
            slot: 'src' ,
            minWidth: TopContext.table.video ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            fixed: 'right' ,
        } ,
    ] ,
    data: [] ,
};

const videoSubtitles = {
    field: [
        {
            type: 'selection' ,
            width: TopContext.table.checkbox ,
            center: TopContext.table.alignCenter ,
            fixed: 'left' ,
        } ,
        {
            title: 'id' ,
            key: 'id' ,
            minWidth: TopContext.table.id ,
            center: TopContext.table.alignCenter ,
            fixed: 'left' ,
        } ,
        {
            title: '名称' ,
            key: 'name' ,
            minWidth: TopContext.table.name ,
            center: TopContext.table.alignCenter ,
        } ,
        {
            title: '字幕源' ,
            minWidth: TopContext.table.src ,
            key: 'src' ,
        } ,
        {
            title: '操作' ,
            slot: 'action' ,
            minWidth: TopContext.table.action ,
            fixed: 'right' ,
        } ,
    ] ,
    data: [] ,
};

export default {

    computed: {
        title () {
            return this.mode === 'edit' ? '编辑' : '添加';
        } ,
    } ,

    data () {
        return {
            val: {
                tab: 'base' ,
                pending: {} ,
                error: {} ,
                selectedIdsForVideo: [] ,
                selectedIdsForSubtitle: [] ,
                modalForVideoProject: false ,
                modalForUser: false ,
                valueForUser: '' ,
                drawer: false ,
            } ,

            // 用户
            users: G.copy(users , true),

            // 关联主体
            videoProjects: G.copy(videoProjects , true),

            // 标签
            tags: [] ,

            // 标签
            dom: {} ,

            // 实例
            ins: {} ,

            uVideoSubtitles: [] ,

            videos: G.copy(videos) ,

            videoSubtitles: G.copy(videoSubtitles) ,

            images: [] ,

            categories: [] ,

            modules: [] ,

            createTime: '' ,

            form: G.copy(form) ,
        };
    } ,
    props: {
        data: {
            type: Object ,
            default () {
                return {};
            } ,
        } ,
        mode: {
            type: String ,
            default: '' ,
        } ,
    } ,

    mounted () {
        this.initDom();
        this.initIns();

    } ,

    methods: {

        genVideoSubtitle () {
            const id = G.randomArray(6 , 'letter' , true);
            return {
                id ,
                name: '' ,
                src: '' ,
                delete: false ,
                error: '' ,
                uploaded: false ,
                uploading: false ,
            };
        } ,

        getCategories (moduleId , callback) {
            this.pending('getCategories' , true);
            Api.category.search({
                module_id: this.form.module_id ,
                type: this.form.type === 'pro' ? 'video_project' : 'video' ,
            } , (res) => {
                this.pending('getCategories' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    G.invoke(callback , null , false);
                    return ;
                }
                this.categories = data;
                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,

        getModules (callback) {
            this.pending('getModules' , true);
            Api.module.all().then((res) => {
                this.pending('getModules' , false);
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(res.message);
                    G.invoke(callback , null , false);
                    return ;
                }
                this.modules = data;
                this.$nextTick(() => {
                    G.invoke(callback , null , true);
                });
            });
        } ,


        moduleChangedEvent (moduleId) {

            this.error({module_id: ''} , false);
            this.form.category_id = '';
            this.form.video_project_id = '';
            this.form.video_project = G.copy(videoProjects.current);
            this.getCategories(moduleId);
        } ,

        typeChangedEvent (type) {
            if (type === 'misc') {
                this.videoProjects.current  = G.copy(videoProjects.current);
                this.form.video_project_id  = '';
            } else {
                this.form.category_id = 0;
            }
        } ,

        initDom () {
            this.dom.tagInput = G(this.$refs['tag-input']);
            this.dom.tagInputOuter = G(this.$refs['tag-input-outer']);
            this.dom.thumb = G(this.$refs.thumb);
            this.dom.video = G(this.$refs.video);
        } ,

        initIns () {
            const self = this;
            this.ins.thumb = new Uploader(this.dom.thumb.get(0) , {
                api: this.thumbApi(),
                mode: 'override' ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.thumb = data.data;
                } ,
                cleared () {
                    self.form.thumb = '';
                } ,
            });

            this.ins.video = new Uploader(this.dom.video.get(0) , {
                api: this.videoApi() ,
                mode: 'override' ,
                multiple: false ,
                clear: true ,
                uploaded (file , data , code) {
                    if (code !== TopContext.code.Success) {
                        this.status(file.id , false);
                        return ;
                    }
                    this.status(file.id , true);
                    self.form.src = data.data;
                } ,
                cleared () {
                    self.form.src = '';
                } ,
            });
        } ,

        searchVideoProject () {
            this.pending('searchImageSubject' , true);
            Api.video_project.search({
                module_id: this.form.module_id ,
                value: this.videoProjects.value ,
            } , (res) => {
                this.pending('searchImageSubject' , false);
                if (res.code !== TopContext.code.Success) {
                    this.error({video_project_id: data});
                    return ;
                }
                this.videoProjects.page = data.current_page;
                this.videoProjects.total = data.total;
                this.videoProjects.data = data.data;
            });
        } ,

        searchVideoProjectEvent (e) {
            if (this.form.module_id < 1) {
                this.error({video_project_id: '请选择模块后操作'});
                return ;
            }
            this.error({video_project_id: ''} , false);
            this.searchVideoProject();
            this._val('modalForVideoProject' , true);
        } ,

        updateVideoProjectEvent (row , index) {
            this.val.error.video_project_id = '';
            this.form.video_project_id = row.id;
            this._val('modalForVideoProject' , false);
            this.videoProjects.data = [];
            this.videoProjects.current = row;
        } ,

        videoProjectPageEvent (page) {
            this.videoProjects.page = page;
            this.searchVideoProject();
        } ,

        searchUser () {
            this.pending('searchUser' , true);
            Api.user.search(this.users.value , (res) => {
                this.pending('searchUser' , false);
                if (res.code !== TopContext.code.Success) {
                    this.error({user_id: data});
                    return ;
                }
                this.users.total = data.total;
                this.users.page = data.current_page;
                this.users.data = data.data;
            });
        } ,


        searchUserEvent (e) {
            // 开始搜索
            this.searchUser();
            this._val('modalForUser' , true);
        } ,

        updateUserEvent (row , index) {
            this.val.error.user_id = '';
            this.form.user_id = row.id;
            this._val('modalForUser' , false);
            this.users.data = [];
            this.users.current = row;
        } ,

        userPageEvent (page) {
            this.users.page = page;
            this.searchUser();
        } ,

        // 获取当前编辑记录详情
        findById (id) {
            this._val('findById' , true);
            return new Promise((resolve , reject) => {
                Api.video.show(id , (res) => {
                    this._val('findById' , false);
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        reject();
                        return ;
                    }
                    this.form = data;
                    resolve();
                });
            });
        } ,

        openFormDrawer () {
            this._val('drawer' , true);
            this.getModules();
            if (this.mode === 'edit') {
                this.getCategories(this.form.module_id);
                this.findById(this.data.id).then((res) => {
                    this.ins.thumb.render(this.form.thumb);
                    this.users.current = this.form.user ? this.form.user : G.copy(users.current);
                    this.videoProjects.current = this.form.video_project ? this.form.video_project : G.copy(videoProjects.current);
                    this.videos.data = this.form.videos;
                    this.videoSubtitles.data = this.form.video_subtitles;
                });
            }
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            this._val('drawer' , false);
            this._val('tab' , 'base');
            this.ins.thumb.clearAll();
            this.ins.video.clearAll();

            this.images             = [];
            this.tags               = [];
            this.uVideoSubtitles    = [];
            this.users              = G.copy(users);
            this.videoProjects      = G.copy(videoProjects);
            this.videos             = G.copy(videos);
            this.videoSubtitles     = G.copy(videoSubtitles);
            this.form               = G.copy(form);
        } ,


        selectedVideoEvent (data) {
            const ids = [];
            data.forEach((v) => {
                ids.push(v.id);
            });
            this.val.selectedIdsForVideo = ids;
        } ,

        destroyVideoEvent (index , record) {
            const pendingKey = 'delete_' + record.id;
            this.pending(pendingKey , true);
            this.destroyVideo(record.id , () => {
                this.pending(pendingKey , false);

            });
        } ,

        destroyVideosEvent () {
            this.pending('destroyVideos' , true);
            this.destroyVideos(this.val.selectedIdsForVideo , (success) => {
                this.pending('destroyVideos' , false);
                if (success) {
                    this.val.selectedIdsForVideo = [];
                }
            });
        } ,

        destroyVideo (id , callback) {
            this.destroyVideos([id] , callback);
        } ,

        destroyVideos (ids , callback) {
            if (ids.length < 1) {
                this.message('warning' ,'请选择待删除的项');
                G.invoke(callback , this , false);
                return ;
            }
            const self = this;
            this.confirmModal('你确定删除吗？'  , (res) => {
                if (res) {
                    Api.video.destroyVideos(ids , (res) => {
                        if (res.code !== TopContext.code.Success) {
                            G.invoke(callback , this , false);
                            this.errorHandle(res.message);
                            return ;
                        }
                        G.invoke(callback , this , true);
                        this.message('success' , '操作成功' , '影响的记录数：' + data);
                        for (let i = 0; i < this.videos.data.length; ++i)
                        {
                            const cur = this.videos.data[i];
                            if (G.contain(cur.id , ids)) {
                                this.videos.data.splice(i , 1);
                                i--;
                            }
                        }
                    });
                    return ;
                }
                G.invoke(callback , this , false);
            });
        } ,


        selectedSubtitleEvent (data) {
            const ids = [];
            data.forEach((v) => {
                ids.push(v.id);
            });
            this.val.selectedIdsForSubtitle = ids;
        } ,

        destroyVideoSubtitle (id , callback) {
            this.destroyVideoSubtitles([id] , callback);
        } ,

        destroyVideoSubtitles (ids , callback) {
            if (ids.length < 1) {
                this.message('warning' ,'请选择待删除的项');
                G.invoke(callback , this , false);
                return ;
            }
            const self = this;
            this.confirmModal('你确定删除吗？'  , (res) => {
                if (res) {
                    Api.video_subtitle.destroyAll(ids , (res) => {
                        if (res.code !== TopContext.code.Success) {
                            G.invoke(callback , this , false);
                            this.errorHandle(res.message);
                            return ;
                        }
                        G.invoke(callback , this , true);
                        this.message('success' , '操作成功' , '影响的记录数：' + data);
                        for (let i = 0; i < this.videoSubtitles.data.length; ++i)
                        {
                            const cur = this.videoSubtitles.data[i];
                            if (G.contain(cur.id , ids)) {
                                this.videoSubtitles.data.splice(i , 1);
                                i--;
                            }
                        }
                    });
                    return ;
                }
                G.invoke(callback , this , false);
            });
        } ,

        destroyVideoSubtitleEvent (index , record) {
            const pendingKey = 'delete_' + record.id;
            this.pending(pendingKey , true);
            this.destroyVideoSubtitle(record.id , () => {
                this.pending(pendingKey , false);
            });
        } ,

        destroyVideoSubtitlesEvent () {
            this.pending('destroyVideoSubtitles' , true);
            this.destroyVideoSubtitles(this.val.selectedIdsForSubtitle , (success) => {
                this.pending('destroyVideoSubtitles' , false);
                if (success) {
                    this.val.selectedIdsForSubtitle = [];
                }
            });
        } ,

        addVideoSubtitleEvent () {
            const videoSubtitle = this.genVideoSubtitle();
            this.uVideoSubtitles.push(videoSubtitle);
        } ,

        videoSubtitleChangeEvent (e , record) {
            const tar   = e.currentTarget;
            const files = tar.files;
            if (files.length < 1) {
                return ;
            }
            record.file = files[0];
            this.uploadVideoSubtitle(record , () => {
                record.uploading = false;
            });
        } ,

        uploadVideoSubtitle (videoSubtitle , callback) {
            if (videoSubtitle.uploaded) {
                // 过滤掉那些上传成功的数据
                return ;
            }
            videoSubtitle.uploaded = false;
            videoSubtitle.uploading = true;
            videoSubtitle.error = '';
            Api.file.uploadSubtitle(videoSubtitle.file , (res) => {
                if (res.code !== TopContext.code.Success) {
                    videoSubtitle.error = msg;
                    videoSubtitle.uploaded = false;
                    G.invoke(callback , null , false);
                    return ;
                }
                videoSubtitle.uploaded = true;
                videoSubtitle.src = data;
                G.invoke(callback , null , true);
            });
        } ,

        submitEvent () {
            if (this.pending('submit')) {
                this.message('warning' , '请求中...请耐心等待');
                return ;
            }
            const callback = (res) => {
                this.pending('submit' , false);
                this.error();
                if (res.code !== TopContext.code.Success) {
                    this.errorHandle(msg , data , code);
                    return ;
                }
                this.successHandle((keep) => {
                    this.$emit('on-success');
                    if (!keep) {
                        this.closeFormDrawer();
                    }
                });
            };
            const form = G.copy(this.form);
            // 字幕上传
            const videoSubtitles = [];
            this.uVideoSubtitles.forEach((v) => {
                videoSubtitles.push({
                    name: v.name ,
                    src: v.src ,
                });
            });
            form.video_subtitles = G.jsonEncode(videoSubtitles);
            this.pending('submit' , true);
            if (this.mode === 'edit') {
                Api.video.update(form.id , form , callback);
                return ;
            }
            Api.video.store(form , callback);
        } ,

        changeEventTest (value) {
            console.log('i-select changed' , value , JSON.stringify(this.modules));
        } ,
    } ,

    watch: {

    } ,
};

