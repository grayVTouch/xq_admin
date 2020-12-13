<template>
    <i-poptip trigger="hover" placement="right" :transfer="true">
        <img :src="src ? src : TopContext.res.notFound" :height="TopContext.table.imageH" class="image" @click.stop="openWindow(src)" alt="" />
        <div slot="content" class="table-preview-image-style">
            <img :src="src ? src : TopContext.res.notFound" class="image" @click.stop="openWindow(src)" alt="" />
        </div>
    </i-poptip>
</template>

<script>
    export default {
        name: "my-table-preview" ,

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
    .table-preview-image-style {

    }

    .table-preview-image-style .image {
        max-width: 400px;
    }

</style>
