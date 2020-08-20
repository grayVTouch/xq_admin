<template>
    <div class="my-uploader run-action-feedback-round" @click="showFileSelector">
        <div class="inner">
            <input type="file" @change="changeEvent" class="hide" ref="file">
            <my-icon v-if="!val.pending.upload && !loading" icon="shangchuan1" size="30"></my-icon>
            <my-loading class="loading" v-if="val.pending.upload || loading"></my-loading>
        </div>
    </div>
</template>

<script>
    export default {
        name: "my-uploader" ,

        data () {
            return {
                val: {
                    pending: {} ,
                } ,
                form: {} ,
            };
        } ,

        props: {
            loading: {
                type: Boolean ,
                default: false ,
            } ,
        } ,

        methods: {
            showFileSelector () {
                this.$refs.file.click();
            } ,

            changeEvent (e) {
                const files = e.currentTarget.files;
                if (files.length < 1) {
                    return ;
                }
                this.upload(files[0]);
            } ,

            upload (file) {
                this.pending('upload' , true);
                Api.file.upload(file , (msg , data , code) => {
                    this.pending('upload' , false);
                    if (code !== TopContext.code.Success) {
                        this.message('error' , msg);
                        return ;
                    }
                    this.$emit('success' , data);
                });
            } ,
        }
    }
</script>

<style scoped>
    .my-uploader {
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        width: 50px;
        height: 52px;
        box-sizing: border-box;
    }

    .my-uploader > .inner {
        padding: 10px;
        position: relative;
    }

    .my-uploader > .inner .loading {
        position: absolute;
        left: 50%;
        top: 50%;
        margin-top: -16px;
        margin-left: -15px;
    }
</style>