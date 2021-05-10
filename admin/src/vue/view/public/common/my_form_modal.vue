<template>
    <Modal
        :title="title"
        :width="width"
        :mask-closable="maskClosable"
        :closable="closable"
        class-name="my-form-modal"
        @on-ok="okEvent"
        @on-cancel="cancelEvent"
        :loading="loading"
        v-model="valueCopy"
    >
        <div class="my-modal-body">
            <slot></slot>
        </div>

        <template slot="footer">
            <slot name="footer"></slot>
        </template>
    </Modal>
</template>

<script>
    export default {
        name: "my-form-modal" ,
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
                type: [Number , String] ,
                default: 600 ,
            } ,
            maskClosable: {
                type: Boolean ,
                default: false ,
            } ,
            closable: {
                type: Boolean ,
                default: false ,
            } ,
            loading: {
                type: Boolean ,
                default: false ,
            } ,
        } ,

        methods: {
            okEvent () {
                this.$emit('on-ok');
            } ,

            cancelEvent () {
                this.$emit('on-cancel');
            } ,


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
    .my-form-modal {
        display: flex;
        display: -webkit-flex;
        align-items: center;
        -webkit-align-items: center;
    }

    .my-form-modal > * {
        top: 0;
    }

    .my-modal-body {
        max-height: 650px;
        overflow: auto;
    }
</style>
