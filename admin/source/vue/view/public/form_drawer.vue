<template>
    <Drawer
            :title="title"
            :type="type"
            :width="width"
            :mask-closable="maskClosable"
            :closable="closable"
            class-name="my-form-drawer"
            v-model="valueCopy">

        <template slot="default">
            <slot></slot>
        </template>

        <template slot="header">
            <slot name="header"></slot>
        </template>

    </Drawer>
</template>

<script>
    export default {
        name: "drawer" ,
        data () {
            return {
                valueCopy: false
            };
        } ,
        model: {
            name: 'value' ,
            event: 'change' ,
        } ,
        props: {
            value: {
                type: Boolean ,
                default: true ,
            } ,
            title: {
                type: String ,
                default: '' ,
            } ,
            type: {
                type: String ,
                default: 'primary' ,
            } ,
            width: {
                type: Number ,
                default: 800 ,
            } ,
            maskClosable: {
                type: Boolean ,
                default: false ,
            } ,
            closable: {
                type: Boolean ,
                default: false ,
            } ,
        } ,

        methods: {

        } ,

        watch: {
            valueCopy (newVal , oldVal) {
                this.$emit('change' , newVal);
            } ,

            value: {
                // 新增 这个选项是因为需要在
                // 参数首次传递的时候就
                // 映射到 valueCopy 上
                // 所以需要这么做
                immediate: true ,
                handler (newVal , oldVal) {
                    this.valueCopy = newVal;
                }
            } ,


        } ,
    }
</script>

<style>
    .my-form-drawer .ivu-drawer-body > * {
        padding-bottom: 16px !important;
    }
</style>