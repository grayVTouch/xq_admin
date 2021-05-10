<template>
    <my-form-modal
            v-model="visible"
            title="视频预览"
            :width="1000"
            :mask-closable="true"
            :closable="true"
    >
        <template slot="footer">
            <i-button v-ripple type="primary" @click="hide">关闭</i-button>
        </template>
        <template slot="default">
            <div class="my-video-block">
                <video class="my-video" :src="src" type="video/mp4" loop autoplay></video>
            </div>
        </template>
    </my-form-modal>
</template>

<script>

    export default {
        name: "my-video-preview" ,

        props: {
            src: {
                default: '' ,
            } ,
        } ,

        data () {
            return {
                visible: false ,
            };
        } ,
        methods: {

            hide () {
                this.visible   = false;
            } ,

            show () {
                this.visible = true;
            } ,

        } ,
    }
</script>

<style scoped>
    .my-video-block {
        width: 100%;
        min-height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .my-video-block .my-video {
        max-width: 100%;
        /*max-height: 100%;*/
    }
</style>
