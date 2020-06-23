<template>
    <i-switch
            :size="size"
            v-model="copyValue"
            :true-value="trueValue"
            :false-value="falseValue"
            :loading="loading"
            @on-change="changeEvent"
            :true-color="trueColor"
            :false-color="falseColor"
    ></i-switch>
</template>

<script>
    export default {
        name: "my-switch" ,

        data () {
            return {
                copyValue: 0 ,
            };
        } ,

        model: {
            prop: 'value' ,
            event: 'change' ,
        } ,

        props: {
            value: {
                default: 0 ,
            } ,
            size: {
                type: String ,
                default: 'small' ,
            } ,

            loading: {
                type: Boolean ,
                default: false ,
            } ,

            trueValue: {
                type: Number ,
                default: 1
            } ,

            falseValue: {
                type: Number ,
                default: 0
            } ,

            trueColor: {
                type: String ,
                default: '#13ce66'
            } ,

            falseColor: {
                type: String ,
                default: '#ff4949'
            } ,

            extra: {
                default: null
            } ,

            'on-change': {
                type: Function ,
                default: null
            }
        } ,



        methods: {
            changeEvent (val) {
                this.$emit('on-change' , val , this.extra);
            } ,
        } ,

        watch: {
            copyValue (newVal , oldVal) {
                this.$emit("change" , newVal);
            } ,

            value: {
                immediate: true ,
                handler (newVal , oldVal) {
                    this.copyValue = newVal;
                } ,
            } ,


        }
    }
</script>

<style scoped>

</style>