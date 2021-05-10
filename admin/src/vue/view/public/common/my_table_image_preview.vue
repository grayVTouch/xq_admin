<template>
    <i-poptip trigger="hover" placement="right" :transfer="true">
        <img :src="src ? src : TopContext.res.notFound" :height="TopContext.table.imageH" class="image" @click.stop="openWindow(src)" alt="" />
        <div slot="content" class="my-table-image-preview">
            <img :src="src ? src : TopContext.res.notFound" class="image" @click.stop="openWindow(src)" alt="" />
        </div>
    </i-poptip>
</template>

<script>
    export default {
        name: "my-table-image-preview" ,

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
    .my-table-image-preview {

    }

    .my-table-image-preview .image {
        max-width: 400px;
    }

</style>
