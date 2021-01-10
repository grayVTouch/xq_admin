<template>
    <div class="view-item">

        <div class="line search">
            <div class="run-title">
                <div class="left">筛选</div>
                <div class="right"></div>
            </div>

            <div class="line">
                <slot name="search"></slot>
            </div>
        </div>

        <div class="line">
            <div class="run-action-title">
                <div class="left">
                    <slot name="action"></slot>
                </div>
                <div class="right">
                    <slot name="page"></slot>
                </div>
            </div>
        </div>

        <div class="line data">

            <div class="run-title">
                <div class="left">数据列表</div>
                <div class="right"></div>
            </div>

            <div class="table">
                <slot name="table"></slot>
            </div>

        </div>

        <div class="line">
            <div class="run-action-title">
                <div class="left">
                    <slot name="action"></slot>
                </div>
                <div class="right">
                    <slot name="page"></slot>
                </div>
            </div>
        </div>

        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "my-base"
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
