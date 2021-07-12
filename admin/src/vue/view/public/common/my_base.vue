<template>
    <div class="view-item">

        <div class="line search" v-if="showSearch">
            <div class="run-title">
                <div class="left">筛选</div>
                <div class="right"></div>
            </div>

            <div class="line">
                <slot name="search"></slot>
            </div>
        </div>

        <div class="line" v-if="showActions">
            <div class="run-action-title">
                <div class="left">
                    <slot name="action" v-if="showAction"></slot>
                </div>
                <div class="right">
                    <slot name="page" v-if="showPage"></slot>
                </div>
            </div>
        </div>

        <div class="line data" v-if="showData">

            <div class="run-title">
                <div class="left">数据列表</div>
                <div class="right"></div>
            </div>

            <div class="table">
                <slot name="table"></slot>
            </div>

        </div>

        <div class="line" v-if="showActions">
            <div class="run-action-title">
                <div class="left">
                    <slot name="action" v-if="showAction"></slot>
                </div>
                <div class="right">
                    <slot name="page" v-if="showPage"></slot>
                </div>
            </div>
        </div>

        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "my-base" ,

        props: {
            showSearch: {
                type: Boolean ,
                default: true ,
            } ,
            showActions: {
                type: Boolean ,
                default: true ,
            } ,
            showAction: {
                type: Boolean ,
                default: true ,
            } ,
            showPage: {
                type: Boolean ,
                default: true ,
            } ,
            showData: {
                type: Boolean ,
                default: true ,
            } ,
        } ,
    }
</script>

<style src="../css/base.css"></style>
<style scoped>
    .view-item > .line {
        margin-bottom: 15px;
    }

    .view-item > .line:nth-last-of-type(1) {
        margin-bottom: 0;
    }

</style>
