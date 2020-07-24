<template>
    <button v-ripple class="ripple" :type="type" @click="clickEvent">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: "ripple-button" ,

        props: {
            background: {
                type: String ,
                default: '' ,
            } ,

            type: {
                type: String ,
                default: 'button' ,
            } ,
        } ,

        mounted () {

        } ,

        methods: {
            clickEvent (e) {
                this.$emit('click' , e);
            } ,
        } ,
    }
</script>

<style scoped>
    .ripple {
        position: relative;
    }
</style>