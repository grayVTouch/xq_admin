<template>
    <div class="item" :data-id="item.id" v-if="item.type === 'view' && item.is_menu && item.enable">

        <!-- 测试 -->
        <div class="function">
            <div class="icon">
                <span class="text" v-if="item.floor > 1"></span>
                <img :src="item.__s_ico" class="image">
            </div>
            <div class="explain">
                <div class="in">
                    <div class="ico"><img src="../../../plugin/InfiniteClassification/image/default/ring.png" class="image"></div>
                    <div class="text">{{ item.cn }}</div>
                </div>
            </div>
            <div class="flag hide">
                <div class="new">新的</div>
                <div class="number">0</div>
                <div class="switch"><img src="../../../plugin/InfiniteClassification/image/default/spread.png" class="image"></div>
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