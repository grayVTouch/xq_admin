
<template>
    <div class='infinite-classification'>
        <div class="list">
            <v-item v-for="v in $store.state.menu" :data="v" :key="v.id"></v-item>
        </div>
    </div>
</template>

<script>
    import item from './item.vue';

    export default {
        name: "v-menu" ,
        data () {
            return {
                // mapping ,
            };
        } ,
        created () {
        } ,
        mounted () {
        } ,
        components: {
            'v-item': item
        }
    }
</script>

<style scoped>
</style>