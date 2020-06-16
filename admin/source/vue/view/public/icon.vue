<template>
    <i class="run-iconfont" :class="{['run-' + icon]: true , 'padding-left': mode === 'left' || mode === 'both' , 'padding-right': mode === 'right' || mode === 'both' , ['f-' + size]: true}"></i>
</template>

<script>
    export default {
        name: "icon" ,

        props: {
            icon: {
                type: String ,
                default: '' ,
            } ,

            mode: {
                type: String ,
                default: 'right' ,
            } ,

            size: {
                type: [Number,String] ,
                default: 14 ,
            } ,
        } ,
    }
</script>

<style scoped>
    .run-iconfont {

    }

    .run-iconfont.padding-left {
        padding-left: 5px;
    }

    .run-iconfont.padding-right {
        padding-right: 5px;
    }
</style>