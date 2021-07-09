<template>
    <div>
        <Modal
                title="图片列表"
                width="1064"
                :mask-closable="maskClosable"
                :closable="closable"
                v-model="visibleCopy"
        >
            <div class="my-modal-body">

                <div class="image-preview-container">
                    <div class="inner">

                        <div class="images">
                            <div class="item" v-for="v in tableData.data" :key="v.id">
                                <div class="preview"><img :src="v[mapping.src]" class="image" alt="" /></div>
                                <div class="actions">
                                    <div class="action view run-action-feedback-round" @click="preview(v)">
                                        <i class="iconfont run-iconfont run-iconfont-chakan f-30"></i>
                                    </div>
                                    <div class="action delete run-action-feedback-round" v-if="!isDisabledDelete">
                                        <i class="iconfont run-iconfont run-iconfont-shanchu f-30"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pager">
                            <my-page
                                    class="run-page-center"
                                    :total="tableData.total"
                                    :size="tableData.size"
                                    :sizes="tableData.sizes"
                                    @on-page-change="pageEvent"
                                    @on-size-change="sizeEvent"
                            ></my-page>
                        </div>
                    </div>
                </div>

            </div>

            <template slot="footer">
                <i-button @click="visibleCopy = false">关闭</i-button>
            </template>
        </Modal>

        <Modal
                title="预览大图"
                :mask-closable="true"
                :closable="true"
                v-model="showPreviewModal"
                class-name="vertical-center-modal"
                width="auto"
        >
            <div class="big-image-preview-container" title="点击查看大图">
                <img :src="current.src" class="image" @click="openWindow(current[mapping.src] , '_blank')" alt="" />
            </div>

            <template slot="footer">
                <i-button type="primary" v-if="!isDisabledViewOriginalImage" @click="openWindow(current[mapping.originalSrc] , '_blank')">查看原图</i-button>
                <i-button type="primary" :disabled="isDisabledPreviewPrev" @click="previewPrev">上一张</i-button>
                <i-button type="primary" :disabled="isDisabledPreviewNext" @click="previewNext">下一张</i-button>
                <i-button @click="showPreviewModal = false">关闭</i-button>
            </template>
        </Modal>
    </div>
</template>

<script>
    const tableData = {
        data: [] ,
        page: 1 ,
        size: 16 ,
        sizes: [16 , 32 , 48 , 64] ,
    };

    const current = {
        id: 0 ,
        src: '' ,
        original_src: '' ,
    };

    export default {
        name: "my-image-preview" ,

        props: {

            visible: {
                type: Boolean ,
                default: false ,
            } ,

            title: {
                type: String ,
                default: '' ,
            } ,

            width: {
                type: [Number , String] ,
                default: 600 ,
            } ,

            maskClosable: {
                type: Boolean ,
                default: true ,
            } ,

            closable: {
                type: Boolean ,
                default: true ,
            } ,

            images: {
                type: Array ,
                default () {
                    return [];
                } ,
            } ,

            mapping: {
                type: Object ,
                default () {
                    return {
                        id: 'id' ,
                        src: 'src' ,
                        originalSrc: 'original_src' ,
                    };
                } ,
            } ,

            isDisabledViewOriginalImage: {
                type: Boolean ,
                default: false ,
            } ,

            isDisabledDelete: {
                type: Boolean ,
                default: false ,
            } ,
        } ,

        data () {
            return {
                visibleCopy: false ,
                showPreviewModal: false ,
                tableData: G.copy(tableData) ,
                current: G.copy(current) ,
            };
        } ,

        computed: {
            isDisabledPreviewPrev () {
                if (!this.showPreviewModal) {
                    return true;
                }
                const index = this.findImageIndexById(this.current.id);
                return index === 0;
            } ,

            isDisabledPreviewNext () {
                if (!this.showPreviewModal) {
                    return true;
                }
                const index = this.findImageIndexById(this.current.id);
                return index === this.images.length - 1;
            } ,
        } ,

        mounted () {

        } ,

        methods: {
            initData () {
                this.tableData.total = this.images.length;
            } ,

            toPage (page) {
                const start  = (page - 1) * this.tableData.size;
                const end    = start + this.tableData.size;

                this.tableData.page = page;
                this.tableData.data = this.images.slice(start , end);
            } ,

            pageEvent (page , size) {
                this.toPage(page);
            } ,

            sizeEvent (size , page) {
                this.tableData.size= size;
                this.toPage(1);
            } ,

            findImageById (id) {
                for (let i = 0; i < this.images.length; ++i)
                {
                    const cur = this.images[i];
                    if (cur[this.mapping.id] === id) {
                        return cur;
                    }
                }
                throw new Error(`未找到【id: ${id}】对应记录`);
            } ,

            findImageIndexById (id) {
                for (let i = 0; i < this.images.length; ++i)
                {
                    const cur = this.images[i];
                    if (cur[this.mapping.id] === id) {
                        return i;
                    }
                }
                throw new Error(`未找到【id: ${id}】对应记录`);
            } ,

            preview (row) {
                this.current = {...row};
                this.showPreviewModal = true;
            } ,

            previewPrev () {
                if (this.images.length <= 1) {
                    return ;
                }
                let index = this.findImageIndexById(this.current.id);
                index = Math.max(0 , index - 1);
                this.current = this.images[index];
            } ,

            previewNext () {
                if (this.images.length <= 1) {
                    return ;
                }
                let index = this.findImageIndexById(this.current.id);
                index = Math.min(this.images.length - 1 , index + 1);
                this.current = this.images[index];
            } ,
        } ,

        watch: {
            images: {
                immediate: true ,
                handler (newVal , oldVal) {
                    this.tableData = G.copy(tableData);
                    this.initData();
                    this.toPage(1);
                } ,
            } ,

            visibleCopy (newVal , oldVal) {
                if (newVal === this.visible) {
                    return ;
                }
                this.$emit('update:visible' , newVal);
            } ,

            visible: {
                // 新增 这个选项是因为需要在
                // 参数首次传递的时候就
                // 映射到 visibleCopy 上
                // 所以需要这么做
                immediate: true ,
                handler (newVal , oldVal) {
                    if (newVal === oldVal) {
                        return ;
                    }
                    this.visibleCopy = newVal;
                }
            } ,
        } ,
    }
</script>

<style scoped>
    .image-preview-container {
        padding: 0 10px;
    }

    .image-preview-container .inner {

    }

    .image-preview-container .inner > * {
        margin-bottom: 20px;
    }

    .image-preview-container .inner > *:nth-last-of-type(1) {
        margin-bottom: 0;
    }

    .image-preview-container .inner .images {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        align-content: flex-start;
        flex-wrap: wrap;
    }

    .image-preview-container .inner .images .item {
        width: 240px;
        height: 135px;
        overflow: hidden;
        margin-right: 10px;
        margin-top: 10px;
        box-shadow: 0 0 5px 0 #9a9a9a;
        box-sizing: border-box;
        position: relative;
    }

    .image-preview-container .inner .images .item:nth-of-type(1) ,
    .image-preview-container .inner .images .item:nth-of-type(2) ,
    .image-preview-container .inner .images .item:nth-of-type(3) ,
    .image-preview-container .inner .images .item:nth-of-type(4)
    {
        margin-top: 0;
    }
    .image-preview-container .inner .images .item:nth-of-type(4n) {
        margin-right: 0;
    }

    .image-preview-container .inner .images .item:hover .actions {
        opacity: 1;
    }

    .image-preview-container .inner .images .item .preview {
        width: inherit;
        height: inherit;
        position: relative;
    }

    .image-preview-container .inner .images .item .preview .image {
        width: 100%;
        /*min-height: 100%;*/
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50% , -50%);
    }

    .image-preview-container .inner .images .item .actions {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0 , 0 , 0 , 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
        opacity: 0;
        transition: all 0.3s ease;
    }


    .image-preview-container .inner .images .item .actions .action {
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        margin-right: 5px;
    }

    .image-preview-container .inner .images .item .actions .action:nth-last-of-type(1) {
        margin-right: 0;
    }

    /**
     * *********************
     * 大图 - 模态框
     * *********************
     */
    .big-image-preview-container {
        overflow: hidden;
        overflow-y: auto;
        max-height: 700px;
    }

    .big-image-preview-container .image {
        max-width: 1000px;
        cursor: pointer;
    }
</style>
