<template>
    <i-select
              v-model="valueCopy"
              filterable
              @on-change="changeEvent"
              :style="`width: ${width}px`"
              :disabled="disabled"
    >
        <i-option v-if="has" :value="top.key">{{ top.value }}</i-option>
        <i-option v-for="(v,k) in data" :value="v[attr.id]" :key="v[attr.id]">
            {{ v[attr.floor] > 1 ? '|' + '_'.repeat((v[attr.floor] - 1) * 10) : '' }}{{ v[attr.name] }}<slot name="extra" :row="v" :index="k"></slot>
        </i-option>
    </i-select>
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
                default: '' ,
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
                default: '' ,
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
            } ,
            width: {
                type: [String , Number] ,
                default: 200 ,
            } ,
            disabled: {
                type: Boolean ,
                default: false ,
            }
        } ,

        methods: {
            changeEvent (value) {
                this.$emit('change' , G.isValid(value) ? value : '');
            } ,
        } ,

        watch: {
            value: {
                // 为了避免初始化的时候在出现重复值导致不应用
                // 所以，必须要加上 immediate
                immediate: true ,
                handler (newVal , oldVal) {
                    if (newVal === this.valueCopy) {
                        return ;
                    }
                    this.valueCopy = newVal;
                }
            } ,
        } ,
    }
</script>

<style scoped>

</style>
