<template>
    <div class="item" :data-id="item.route" v-if="!item.hidden">
<!--    <div class="item" :data-id="item.route" v-if="true">-->
        <div class="action" v-ripple>
            <div class="text">{{ item.name }}</div>
            <div class="ico hide"><i class="icon run-iconfont run-iconfont-arrow"></i></div>
        </div>
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