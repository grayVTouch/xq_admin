<template>
    <div>
        <i-select v-model="valueCopy" :style="'width:' + width + 'px'">
            <i-option v-if="has" :value="top.key">{{ top.value }}</i-option>
            <i-option v-for="v in data" :value="v[attr.id]" :key="v[attr.id]">{{ v[attr.floor] > 1 ? '|' + '_'.repeat((v[attr.floor] - 1) * 4) : '' }}{{ v[attr.name] }}</i-option>
        </i-select>
    </div>
</template>

<script>
    export default {
        name: "my-select" ,
        data () {
            return {
                valueCopy: 0 ,
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
            width: {
                type: Number ,
                default: 200 ,
            } ,
            // 是否需要顶级项
            has: {
                type: Boolean ,
                default: true ,
            } ,
            top: {
                type: Object ,
                default () {
                    return {
                        key: 0 ,
                        value: '顶级项' ,
                    };
                } ,
            } ,
            attr: {
                type: Object ,
                default () {
                    return {
                        id: 'id',
                        floor: 'floor',
                        name: 'name',
                    }
                }
            }
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

<style scoped>

</style>