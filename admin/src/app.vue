<template>
<!--    <transition name="fade-in-out">-->
        <router-view class="fade-in-out"></router-view>
<!--    </transition>-->
</template>

<script>
    export default {
        name: "app" ,
    }
</script>

<style scoped>
    /*.fade-in-out {*/
    /*    transition: all 0.4s;*/
    /*}*/

    /*.fade-in-out-leave {*/
    /*    opacity: 1;*/
    /*    transform: translateX(0%);*/
    /*}*/

    /*.fade-in-out-leave-to {*/
    /*    opacity: 0;*/
    /*    transform: translateX(-100%);*/
    /*}*/

    /*.fade-in-out-enter {*/
    /*    opacity: 0;*/
    /*    transform: translateX(100%);*/
    /*}*/

    /*.fade-in-out-enter-to {*/
    /*    opacity: 1;*/
    /*    transform: translateX(0%);*/
    /*}*/
</style>
