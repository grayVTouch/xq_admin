const videoSubject = {
    // 当前播放的视频
    current: {} ,
    videos: [] ,
};

const indexRange = {
    // 当前索引类型
    current: '' ,
    // 当前选择的剧集范围
    range: '1-30' ,
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

            videoSubject: G.copy(videoSubject) ,

            // 当前索引范围
            indexRange: G.copy(indexRange) ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
        this.initEvent();
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
                soundStep: 0.2 ,
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
                    // console.log('switch video');
                    self.videoSubject.current = self.videoSubject.videos[index - 1];

                } ,
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.video_subject.show(this.id , (msg , data , code) => {
                this.pending('getData' , false);
                if (code !== TopContext.code.Success) {
                    return this.errorHandleAtHomeChildren(msg , data , code);
                }
                // 数据处理
                this.handleData(data);

                // console.log(G.jsonEncode(data));
                // debugger

                // 生成剧集信息
                this.generateIndexRange(data.count);

                this.videoSubject = data;

                this.$nextTick(() => {
                    this.initVideoPlayer();
                });
            });
        } ,

        handleData (data) {
            data.current = {};
            data.videos = data.videos ? data.videos : [];
            data.videos.forEach((v) => {
                v.videos           = v.videos ? v.videos : [];
                v.video_subtitles  = v.video_subtitles ? v.video_subtitles : [];

                // 当前显示的元素类型
                v.show_type = 'image';
                // 视频是否加载完成
                v.video_loaded = false;
                // 视频是否已经初始化（避免重复初始化）
                v.init_video_preview = false;
                // 视频加载进度
                v.video_loaded_ratio = 0;
            });
        } ,

        generateIndexRange (count) {
            const range = this.indexRange.indexGroupCount * this.indexRange.split;
            let i = 1;
            let obj;
            let groupCount = 1;
            while (i <= count)
            {
                if (!obj) {
                    obj = {
                        min: i ,
                        max: i ,
                    };
                }
                if (i >= groupCount * this.indexRange.split || i === count) {
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

    } ,
}