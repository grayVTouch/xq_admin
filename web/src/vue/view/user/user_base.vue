<template>
    <div class="user-view">
        <div class="run-action-title">
            <div class="left run-weight">
                <slot name="title"></slot>
            </div>
            <div class="right">
                <slot name="action"></slot>
            </div>
        </div>
        <div class="content">
            <slot name="content"></slot>
        </div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "user_base"
    }
</script>

<style scoped>
    .user-view .run-action-title {
        padding: 0 20px;
    }
</style>