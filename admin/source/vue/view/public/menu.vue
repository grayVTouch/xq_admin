
<template>
    <div class='infinite-classification'>
        <div class="list">
            <v-item v-for="v in list" :item="v" :key="v.id"></v-item>
        </div>
    </div>
</template>

<script>
    import item from './item.vue';

    export default {
        name: "v-menu" ,
        props: {
            list: {
                type: Array ,
                required: true
            }
        } ,
        components: {
            'v-item': item
        }
    }
</script>

<style scoped>
</style>