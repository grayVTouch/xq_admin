<template>
    <i-poptip trigger="hover" placement="right" :transfer="true">
        <i-button @click.stop="openWindow(src)">悬浮预览</i-button>
        <div slot="content" class="my-table-image-preview-by-button">
            <img :src="src ? src : TopContext.res.notFound" class="image" @click.stop="openWindow(src)" alt="" />
        </div>
    </i-poptip>
</template>

<script>
    export default {
        name: "my-table-image-preview-by-button" ,

        props: {
            src: {
                default: '' ,
            }
        }
    }
</script>

<style scoped>
    /**
     * ***************
     * 表格大图预览样式
     * ***************
     */
    .my-table-image-preview-by-button {

    }

    .my-table-image-preview-by-button .image {
        max-width: 400px;
    }

</style>
