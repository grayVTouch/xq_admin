<template>
    <div class="item" :data-id="data.id">

        <!-- 测试 -->
        <div class="function">
            <div class="icon">
                <span class="text" v-if="data.floor > 1"></span>
                <img :src="data.s_ico_explain" class="image">
            </div>
            <div class="explain">
                <div class="in">
                    <div class="ico"><img src="../../../plugin/InfiniteClassification/image/default/ring.png" class="image"></div>
                    <div class="text">{{ data.cn }}</div>
                </div>
            </div>
            <div class="flag hide">
                <div class="new">新的</div>
                <div class="number">10</div>
                <div class="switch"><img src="../../../plugin/InfiniteClassification/image/default/spread.png" class="image"></div>
            </div>
        </div>

        <div class="child">
            <div class="list">
                <v-item v-for="v in data.children" :data="v" :key="v.id"></v-item>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-item" ,
        props: {
            data: {
                type: Object ,
                required: true ,
                default: {}
            }
        }
    }
</script>

<style scoped></style>