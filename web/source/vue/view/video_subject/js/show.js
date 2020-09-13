const videoSubject = {
    // 当前播放的视频
    current: {} ,
    videos: [] ,
};

const indexRange = {
    // 当前索引类型
    current: {
        min: 0 ,
        max: 0 ,
        value: ''
    } ,
    // 分割的集数
    split: 30 ,
    // 正常显示的剧集分组数量
    indexGroupCount: 3 ,
    // 剧集分组
    group: {
        index: [
            // {
            //     min: 1 ,
            //     max: 30 ,
            // } ,
        ] ,
        other: [] ,
    } ,

    videos: [] ,
};

export default {
    name: "show" ,

    props: ["id"] ,

    data () {
        return {
            dom: {} ,

            val: {
                // 加载更多剧集
                loadMoreIndex: false ,
            } ,

            ins: {} ,

            // 当前视频专题
            videoSubject: G.copy(videoSubject) ,

            // 当前索引范围
            indexRange: G.copy(indexRange) ,

            // 是否首次加载视频（索引）
            onceLoadVideosInIndex: true ,

            // 视频专题
            videoSubjectsInSeries: [] ,
        };
    } ,

    computed: {

    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getVideoSubject(() => {
            // 生成剧集信息
            this.generateIndexRange(this.videoSubject.min_index , this.videoSubject.max_index);
            // 初始化视频播放器
            this.initVideoPlayer();
            // 获取系列下的视频专题
            this.getVideoSubjectsInSeries();
        });
        this.initEvent();
    } ,

    beforeRouteUpdate (to , from , next) {
        this.reload();
    } ,

    methods: {
        initDom () {
            this.dom.win = G(window);
            this.dom.videoContainer = G(this.$refs['video-container']);

        } ,

        initIns () {

        } ,

        initVideoPlayer () {
            const self = this;
            const playlist = [];
            this.videoSubject.videos.forEach((v) => {
                const definition = [];
                const subtitle = [];

                v.videos.forEach((v1) => {
                    definition.push({
                        name: v1.definition ,
                        src: v1.__src__ ,
                    });
                });

                v.video_subtitles.forEach((v1) => {
                    subtitle.push({
                        name: v1.name ,
                        src: v1.__src__ ,
                    });
                });

                playlist.push({
                    name: v.name ,
                    thumb: v.__thumb__ ,
                    preview: {
                        src: v.__preview__ ,
                        width: v.preview_width ,
                        height: v.preview_height ,
                        duration: v.preview_duration ,
                        count: v.preview_line_count ,
                    } ,
                    definition ,
                    subtitle ,
                });
            });

            this.ins.videoPlayer = new VideoPlayer(this.dom.videoContainer.get(0) , {
                // 海报
                // poster: './res/poster.jpg' ,
                poster: '' ,
                // 单次步进时间，单位：s
                step: 30 ,
                // 音频步进：0-1
                // soundStep: 0.2 ,
                // 视频源
                playlist ,
                // 当前播放索引
                index: 1 ,
                // 静音
                muted: false ,
                // 音量大小
                volume: 1 ,
                // 开启字幕
                enableSubtitle: true ,
                // definition: '480P' ,
                // 当视频播放结束时的回调
                ended () {
                    // 自动播放下一集
                    this.next();
                } ,

                switch (index) {
                    self.videoSubject.current = self.videoSubject.videos[index - 1];
                    self.selectedIndexRange(index);
                    if (self.onceLoadVideosInIndex) {
                        self.onceLoadVideosInIndex = false;
                        self.videosInRange(self.indexRange.current.min , self.indexRange.current.max);
                    }
                } ,
            });
        } ,

        getVideoSubject (callback) {
            this.pending('getVideoSubject' , true);
            Api.video_subject.show(this.id , (msg , data , code) => {
                this.pending('getVideoSubject' , false);
                if (code !== TopContext.code.Success) {
                    return this.errorHandleAtHomeChildren(msg , data , code);
                }
                // 数据处理
                this.handleData(data);

                this.videoSubject = data;

                this.$nextTick(() => {
                    G.invoke(callback);
                });
            });
        } ,

        handleData (data) {
            data.current = {};
            data.videos = data.videos ? data.videos : [];
            data.videos.forEach((v) => {
                v.videos           = v.videos ? v.videos : [];
                v.video_subtitles  = v.video_subtitles ? v.video_subtitles : [];
            });
        } ,

        generateIndexRange (min , max) {
            let i = min;
            let obj;
            let groupCount = 1;
            while (i <= max)
            {
                if (!obj) {
                    obj = {
                        min: i ,
                        max: i ,
                    };
                }
                if (i >= min + groupCount * this.indexRange.split || i === max) {
                    obj.max = i;
                    if (groupCount <= this.indexRange.indexGroupCount) {
                        this.indexRange.group.index.push(obj);
                    } else {
                        this.indexRange.group.other.push(obj);
                    }
                    groupCount++;
                    obj = null;
                }
                i++;
            }
        } ,

        // 选中其中一个索引范围
        selectedIndexRange (index) {
            const indexRange = this.indexRange.group.index.concat(this.indexRange.group.other);
            for (let i = 0; i < indexRange.length; ++i)
            {
                const cur = indexRange[i];
                if (index >= cur.min && index <= cur.max) {
                    this.indexRange.current = {
                        min: cur.min ,
                        max: cur.max ,
                        value: cur.min + '-' + cur.max ,
                        more: this.isIndexRangeInMore(cur.min , cur.max) ,
                    };
                    break;
                }
            }
        } ,

        showMoreIndex () {
            this._val('loadMoreIndex' , true);
        } ,

        hideMoreIndex () {
            this._val('loadMoreIndex' , false);
        } ,

        initEvent () {
            this.dom.win.on('click' , this.hideMoreIndex.bind(this));
        } ,

        showVideo (record) {
            const video = G(this.$refs['video-' + record.id]);
            record.show_type = 'video';
            if (record.video_loaded) {
                video.native('currentTime' , 0);
                video.origin('play');
            } else {
                if (!record.init_video_preview) {
                    record.init_video_preview = true;
                    G.ajax({
                        url: record.__simple_preview__ ,
                        method: 'get' ,
                        // 下载事件
                        progress (e) {
                            if (!e.lengthComputable) {
                                return ;
                            }
                            record.video_loaded_ratio = e.loaded / e.total;
                        } ,
                        success () {
                            video.on('loadeddata' , () => {
                                record.video_loaded = true;
                                record.video_loaded_ratio = 1;
                            });
                            video.native('src' , record.__simple_preview__);
                        } ,
                    });
                }
            }
        } ,

        hideVideo (record) {
            record.show_type = 'image';
            if (record.video_loaded) {
                const video = G(this.$refs['video-' + record.id]);
                video.origin('pause');
            }
        } ,

        videosInRange (min , max) {
            this.pending('videosInRange' , true);
            // 请求数据
            Api.video_subject.videosInRange(this.videoSubject.id , {
                min ,
                max ,
            } , (msg , data , code) => {
                this.pending('videosInRange' , false);
                if (code !== TopContext.code.Success) {
                    return this.errorHandle(msg , data , code);
                }
                data.forEach((v) => {
                    // 当前显示的元素类型
                    v.show_type = 'image';
                    // 视频是否加载完成
                    v.video_loaded = false;
                    // 视频是否已经初始化（避免重复初始化）
                    v.init_video_preview = false;
                    // 视频加载进度
                    v.video_loaded_ratio = 0;
                });
                this.indexRange.videos = data;
            });
        } ,

        isIndexRangeInMore (min , max) {
            for (let i = 0; i < this.indexRange.group.index.length; ++i)
            {
                const cur = this.indexRange.group.index[i];
                if (cur.min == min && cur.max == max) {
                    return false;
                }
            }
            return true;
        } ,

        switchIndexRangeByMinAndMax (min , max) {
            this.indexRange.current = {
                min ,
                max ,
                value:  min + '-' + max ,
                more: this.isIndexRangeInMore(min , max) ,
            };
            this.videosInRange(min , max);
            this.hideMoreIndex();
        } ,

        // 获取视频系列
        getVideoSubjectsInSeries () {
            this.pending('getVideoSubjectsInSeries' , true);
            Api.video_subject.videoSubjects(this.videoSubject.video_series_id , {
                video_subject_id: this.videoSubject.id ,
            } , (msg , data , code) => {
                this.pending('getVideoSubjectsInSeries' , false);
                if (code !== TopContext.code.Success) {
                    return this.errorHandle(msg , data , code);
                }
                this.videoSubjectsInSeries = data;
            });
        } ,

    } ,
}