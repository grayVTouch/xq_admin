<template>
    <div class="my-avatar">
        <div class="mask" v-if="mask">
            <div class="top">{{ topVal }}</div>
            <div class="btm">{{ btmVal }}</div>
        </div>
        <img :src="src ? src : TopContext.res.avatar" class="image">
    </div>
</template>

<script>
    export default {
        name: "v-avatar" ,
        props: {
            src: {
                type: String ,
                default:''
            } ,
            topVal: {
                type: String ,
                default: '' ,
            } ,
            btmVal: {
                type: String ,
                default: '' ,
            } ,
            mask: {
                type: Boolean ,
                default: false ,
            } ,
        } ,
    }
</script>

<style scoped>
    .my-avatar {
        display: inline-block;
        overflow: hidden;
        vertical-align: middle;
        position: relative;
    }

    .my-avatar:hover > .mask {
        width: 100%;
        height: 100%;
        opacity: 1;
    }

    .my-avatar > .mask {
        opacity: 0;
        cursor: pointer;
        position: absolute;
        width: 0;
        height: 0;
        background-color: rgba(0 , 0 , 0 , 0.6);
        left: 50%;
        top: 50%;
        border: 3px solid #fff;
        overflow: hidden;
        transition: all 0.3s linear;
        border-radius: 50%;
        transform: translate(-50% , -50%);
        -webkit-transform: translate(-50% , -50%);
        -moz-transform: translate(-50% , -50%);
        -ms-transform: translate(-50% , -50%);
        -o-transform: translate(-50% , -50%);
    }

    .my-avatar > .mask:after {
        display: block;
        content: '';
        width: 60%;
        height: 2px;
        background-color: white;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50% , -50%);
        -webkit-transform: translate(-50% , -50%);
        -moz-transform: translate(-50% , -50%);
        =ms-transform: translate(-50% , -50%);
        -o-transform: translate(-50% , -50%);
    }

    .my-avatar > .mask > * {
        height: 50%;
        display: flex;
        justify-content: center;
        color: #fff;
        white-space: nowrap;
    }

    .my-avatar > .mask > .top {
        align-items: flex-end;
        padding-bottom: 1px;
    }

    .my-avatar > .mask > .btm {
        padding-top: 1px;
        align-items: flex-start;
    }

    .my-avatar > .image {
        width: 100%;
        min-height: 100%;
        vertical-align: top;
    }
</style>
