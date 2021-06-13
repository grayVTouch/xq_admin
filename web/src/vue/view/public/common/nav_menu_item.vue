<template>
    <div class="item" :data-id="item.route" v-if="!item.hidden">
<!--    <div class="item" :data-id="item.route" v-if="true">-->
        <a class="action" v-ripple :href="item.route">
            <span class="text">{{ item.name }}</span>
            <span class="ico hide"><i class="icon run-iconfont run-iconfont-arrow"></i></span>
        </a>
        <div class="list">
            <my-nav-menu-item v-for="v in item.children" :key="v.route" :item="v"></my-nav-menu-item>
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
