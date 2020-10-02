
<template>
    <div class='infinite-classification'>
        <div class="list">
            <v-item v-for="v in copyData" :item="v" :key="v.id"></v-item>
        </div>
    </div>
</template>

<script>
    import item from './item.vue';

    export default {
        name: "v-menu" ,
        data () {
            return {
                copyData: [] ,
            };
        } ,
        props: {
            data: {
                type: Array ,
                required: true
            }
        } ,
        mounted () {

        } ,

        components: {
            'v-item': item
        } ,

        watch: {
            data: {
                immediate: true ,
                handler (data) {
                    // 深拷贝数据
                    const copyData = G.copy(data , true);
                    // 数据处理（附加相关数据）
                    G.tree.handle(copyData);
                    // 数据拷贝
                    this.copyData = copyData;
                } ,
            } ,
        } ,
    }
</script>

<style scoped>
</style>
