
<template>
    <div class='infinite-classification'>
        <div class="list">
            <v-item v-for="v in permissions" :item="v" :key="v.id"></v-item>
        </div>
    </div>
</template>

<script>
    import item from './item.vue';

    export default {
        name: "v-menu" ,

        data () {
            return {
                ins: {} ,
                slidebarData: [] ,
            };
        } ,

        mounted () {
            this.$nextTick(() => {
                this.render();
            });
        } ,

        components: {
            'v-item': item
        } ,

        computed: {
            permissions () {
                const permissions = G.copy(this.state().permissions);
                G.tree.uHandle(permissions , (v) => {
                    v.id = G.randomArray(32 , 'mixed' , true);
                    // 检查当前登录用户是否具备查看权限
                });
                return permissions;
            } ,
        } ,

        methods: {

            render () {
                const self = this;
                this.ins.ic = new InfiniteClassification(this.$el , {
                    // 菜单展开动画过渡时间
                    time: 200 ,
                    // 次要的图标类型，new || number || switch
                    icon: 'switch' ,
                    // 初始状态，spread || shrink
                    status: 'shrink' ,
                    // 层级视觉显示效果
                    amount: 12 ,
                    // 同层级是否互斥
                    exclution: false ,
                    // 是否菜单也可被选中
                    menuFocus: false ,
                    // 点击项后是否选中
                    focus: true ,
                    // 是否选中顶级菜单
                    topFocus: false ,
                    // 子级项点击后回调
                    child (id) {
                        self.$emit('click' , id);
                        // const topRoute = self.topRoute(id);
                        // let route = self.findRouteById(id);
                        // let text = self.genTabName(topRoute , route);
                        // self.open(text , route.path);
                    }
                });
            } ,
        } ,

    }
</script>

<style scoped>
</style>
