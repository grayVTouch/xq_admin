<template>
    <a v-ripple class="ripple" :href="href ? href : 'javascript: ;'" @click="clickEvent">
        <slot></slot>
    </a>
</template>

<script>
    export default {
        name: "ripple-button" ,

        props: {
            background: {
                type: String ,
                default: '' ,
            } ,

            href: {
                type: String ,
                default: '' ,
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