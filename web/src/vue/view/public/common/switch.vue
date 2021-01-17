<template>
    <div class="run-switch" ref="run-switch" @click="modeSwitch" :class="{'run-switch-off': valueCopy === falseValue , 'run-switch-on': valueCopy === trueValue , 'run-switch-small': size === 'small'}">
        <div class="run-switch-ctl"></div>
    </div>
</template>

<script>
    export default {
        name: "my-switch" ,

        data () {
            return {
                valueCopy: 0 ,
                dom: {} ,
            };
        } ,

        mounted () {
            this.dom.switch = G(this.$refs['run-switch']);
        } ,

        model: {
            prop: 'value' ,
            event: 'on-change' ,
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
                default: 1
            } ,

            falseValue: {
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
            modeSwitch () {
                if (this.dom.switch.hasClass('run-switch-on')) {
                    this.valueCopy = this.falseValue;
                } else {
                    this.valueCopy = this.trueValue;
                }
                this.$emit('on-change' , this.valueCopy , this.extra);
            } ,
        } ,

        watch: {
            value: {
                immediate: true ,
                handler (newVal , oldVal) {
                    this.valueCopy = newVal;
                } ,
            } ,


        }
    }
</script>

<style scoped>

</style>