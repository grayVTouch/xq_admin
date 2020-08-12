<template>
    <select v-model="valueCopy" @change="changeEvent" class="form-select">
        <option :value="empty">请选择...</option>
        <option v-for="v in data" :value="v[attr.id]" :key="v[attr.id]">{{ v[attr.name] }}</option>
    </select>
</template>

<script>
    export default {
        name: "my-select" ,
        data () {
            return {
                valueCopy: '' ,
            };
        } ,
        model: {
            name: 'value' ,
            event: 'change' ,
        } ,
        props: {
            value: {
                type: [String , Number] ,
                required: true ,
            },
            data: {
                type: Array ,
                required: true ,
            } ,
            // 未选择时的默认值
            empty: {
                default: '' ,
            } ,
            attr: {
                type: Object ,
                default () {
                    return {
                        id: 'id',
                        name: 'name',
                    }
                }
            }
        } ,

        methods: {
            changeEvent (e) {
                this.$emit('change' , e.target.value);
            } ,
        } ,

        watch: {
            value: {
                // 为了避免初始化的时候在出现重复值导致不应用
                // 所以，必须要加上 immediate
                immediate: true ,
                handler (newVal , oldVal) {
                    this.valueCopy = newVal;
                }
            } ,
        } ,
    }
</script>

<style scoped>

</style>