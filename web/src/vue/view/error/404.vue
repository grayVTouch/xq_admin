<template>
    <div class="view">
        <div class="message">404|未找到路由</div>
    </div>
</template>

<script>
    export default {
        name: "page-not-found"
    }
</script>

<style scoped>
    .view {
        padding: 20px;
        background-color: white;
        height: 100%;
        position: relative;
        display: flex;
        display: -webkit-flex;
        justify-content: center;
        align-items: center;
    }

    .view .message {
        color: #555;
        font-weight: bold;
    }
</style>