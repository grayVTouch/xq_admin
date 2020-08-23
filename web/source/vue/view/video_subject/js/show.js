const videoSubject = {

    videos: [] ,
};

export default {
    name: "show" ,

    props: ["id"] ,

    data () {
        return {
            dom: {} ,
            val: {} ,
            ins: {} ,
            videoSubject: G.copy(videoSubject) ,
        };
    } ,

    mounted () {
        this.initDom();
        this.initIns();
        this.getData();
    } ,

    methods: {
        initDom () {
            this.dom.videoContainer = G(this.$refs['video-container']);

        } ,

        initIns () {

        } ,

        initVideoPlayer () {
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
                muted: true ,
                // 音量大小
                volume: 1 ,
                // 开启字幕
                enableSubtitle: true ,
                // definition: '480P' ,
                // 当视频播放结束时的回调
                ended: null ,
            });
        } ,

        getData () {
            this.pending('getData' , true);
            Api.video_subject.show(this.id , (msg , data , code) => {
                this.pending('getData' , false);
                if (code !== TopContext.code.Success) {
                    return this.errorHandleAtHomeChildren(msg , data , code);
                }

                this.handleData(data);

                this.videoSubject = data;
                this.$nextTick(() => {
                    this.initVideoPlayer();
                });
            });
        } ,

        handleData (data) {
            data.videos = data.videos ? data.videos : [];
            data.videos.forEach((v) => {
                v.videos           = v.videos ? v.videos : [];
                v.video_subtitles  = v.video_subtitles ? v.video_subtitles : [];
            });
        } ,

    } ,
}