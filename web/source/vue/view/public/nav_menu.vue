<template>
    <div class="__nav__">
        <div class="list">
            <my-nav-menu-item v-for="v in data" :key="v.id" :item="v"></my-nav-menu-item>
        </div>
    </div>
</template>

<script>
    export default {
        name: "nav_menu" ,
        props: {
            data: {
                type: Array ,
                default () {
                    return [];
                } ,
            } ,
        } ,
    }
</script>

<style>
    /**
     * **************************
     * Nav 导航栏插件部分样式重写
     * **************************
     */
    .__nav__ .list {
        /*background-color: #464646;*/
        background-color: transparent;
        color: inherit;
    }

    .__nav__ > .list > .item .list {
        background-color: #464646;
    }

    .__nav__ .list .item:hover > .action {
        background-color: #656565;
    }

    .__nav__ .list .item {
        height: auto;
        line-height: inherit;
    }
</style>