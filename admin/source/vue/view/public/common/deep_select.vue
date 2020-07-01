<template>
    <select v-model="valueCopy" @change="changeEvent" class="form-select">
        <option v-if="has" :value="top.key">{{ top.value }}</option>
        <option v-else :value="empty">请选择...</option>
        <option v-for="v in data" :value="v[attr.id]" :key="v[attr.id]">{{ v[attr.floor] > 1 ? '|' + '_'.repeat((v[attr.floor] - 1) * 10) : '' }}{{ v[attr.name] }}</option>
    </select>
</template>

<script>
    export default {
        name: "my-deep-select" ,
        data () {
            return {
                // 特殊，请务必指定一个 下拉列表中不存在的值！
                // 这样是为了避免首次绑定一个默认值时
                // 就触发 on-change 事件，如果首次绑定就触发
                // 这个时候由于 option 可能还没有渲染所以 on-change
                // 中就收到的当前选中项实际上是 undefined !
                // 然后又将 undefined 更新到父组件，父组件
                // 又回传给当前组件，当前组件又对数据类型做了
                // 限定，比如 String / Number ，这样就会报错
                // 另外一个问题，由于当前 v-model 的值已经被更新成一个 新值
                // 所以实际列表渲染后，仍然不会按照期望选中首次提供的默认值
                // 那个默认值实际上是被首次绑定数据时触发的 on-change 给修改了！
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
            // 是否需要顶级项
            has: {
                type: Boolean ,
                default: true ,
            } ,
            // 未选择时的默认值
            empty: {
                default: 0 ,
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