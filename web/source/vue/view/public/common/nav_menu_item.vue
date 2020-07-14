<template>
    <div class="item" :data-id="item.value" v-if="item.is_menu">
        <a class="action" :href="`#${item.value}`" v-ripple>
            <div class="text">{{ item.name }}</div>
            <div class="ico hide"><i class="icon run-iconfont run-iconfont-arrow"></i></div>
        </a>
        <div class="list">
            <my-nav-menu-item v-for="v in item.children" :key="v.id" :item="v"></my-nav-menu-item>
        </div>
    </div>
</template>

<script>
    export default {
        name: "nav_menu_item" ,
        props: {
            item: {
                type: Object ,
                default () {
                    return {};
                } ,
            } ,
        } ,
    }
</script>

<style scoped>

</style>