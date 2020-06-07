<template>
    <div ref="functions" class="function">

        <!-- 无限极分类 -->
        <div class='infinite-classification'>
            <div class="list">
                <v-function v-for="v in $store.state.mapping" :_function_="v" :key="v.path"></v-function>
            </div>
        </div>

    </div>
</template>

<script>
    import _function_ from './function.vue';

    export default {
        name: "v-functions" ,
        data () {
            return {
                instance: {} ,
                dom: {} ,
                // 默认打开的功能菜单
                functions: ['user/list']
            };
        } ,
        props: {
            _instance: {
                type: Object ,
                required: true
            } ,
        } ,
        components: {
            'v-function': _function_
        } ,
        mounted () {
            let self = this;
            this.dom.functions = G(this.$refs.functions);
            this.instance.ic = new InfiniteClassification(this.dom.functions.get(0) , {
                id:  this.functions ,
                time: 200 ,
                focus: false ,
                topFocus: false ,
                status: 'spread' ,
                exclution: false ,
                click (id) {
                    self.createTab(id);
                }
            });

            // 初始化创建标签
            this.functions.forEach(id => {
                this.createTab(id);
            });
        } ,
        methods: {
            createTab (id) {
                // 数据加载完成之后调用（props 数据未加载进来啊等）
                this.$nextTick(() => {
                    let isEmpty = this.instance.ic.isEmpty(id);

                    if (!isEmpty) {
                        return ;
                    }

                    var data = this.instance.ic.data(id);
                    var name = data.top ? data.top + '-' + data.name : data.name;

                    // 创建标签
                    this._instance.tab.create({
                        text: name ,
                        attr: {
                            path: id
                        }
                    });
                });
            }
        }
    }
</script>

<style scoped>

</style>