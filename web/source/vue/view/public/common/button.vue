<template>
    <button class="ripple" type="type" @click="clickEvent">
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
            const button = G(this.$el);
            const color = button.getStyleVal('color');
            const background = this.background ? this.background : color;
            new TouchFeedback(button.get(0) , {
                // time: 300 ,
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