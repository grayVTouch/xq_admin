<template>
    <div class="item" :data-id="item.id">
        <!-- 测试 -->
        <div class="function run-action-feedback">
            <div class="icon">
                <span class="text" v-show="item.floor > 1"></span>
                <img :src="item.__s_ico__" class="image">
            </div>
            <div class="explain">
                <div class="in">
                    <div class="ico"><i class="run-iconfont run-iconfont-huanxingtu"></i></div>
                    <div class="text">{{ item.name }}</div>
                </div>
            </div>
            <div class="flag hide">
                <div class="new">新的</div>
                <div class="number">0</div>
                <div class="switch"><i class="run-iconfont run-iconfont-top_zhankai_icon"></i></div>
            </div>
        </div>

        <div class="child">
            <div class="list">
                <v-item v-for="v in item.children" :item="v" :key="v.id"></v-item>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-item" ,
        props: {
            item: {
                type: Object ,
                required: true ,
                default: {}
            }
        }
    }
</script>

<style scoped></style>