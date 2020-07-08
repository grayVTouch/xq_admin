<template>
    <a class="ripple" :href="href ? href : 'javascript: ;'" @click="clickEvent">
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
            const button = G(this.$el);
            const color = button.getStyleVal('color');
            const background = this.background ? this.background : color;
            new TouchFeedback(button.get(0) , {
                backgroundColor: background ,
            });
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