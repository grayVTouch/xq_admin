<template>
    <div class="view">
        <my-navigation ref="navigation" :topRoute="topRoute" :position="position"></my-navigation>
        <div class="dynamic" ref="dynamic">
            <!-- 匿名插槽 -->
            <slot></slot>
        </div>
        <my-full-loading ref="loading"></my-full-loading>
    </div>
</template>

<script>
    import navigation from './navigation.vue';

    export default {
        name: "my-base" ,
        data () {
            return {
                topRoute: this.$root.topRoute ,
                position: this.$root.position ,
                dom: {} ,
            };
        } ,
        components: {
            "my-navigation": navigation
        } ,

        mounted () {
            console.log(this.$el);

            // return ;
            this.dom.root = G(this.$el);
            this.dom.navigation = G(this.$refs.navigation.$el);
            this.dom.dynamic = G(this.$refs.dynamic);
            this.dom.win = G(window);
            //
            // this.initDynamicH();
            // this.dom.win.on('resize' , this.initDynamicH.bind(this) , true , false);
        } ,

        methods: {
            initDynamicH () {
                const maxH = this.dom.root.height('border-box');

                const navigationH = this.dom.navigation.height('border-box');
                const minH = 400;
                const dynamicH = Math.max(minH , maxH - navigationH);
                this.dom.dynamic.css({
                    height: dynamicH + 'px'
                });
            } ,

            show () {
                this.$refs.loading.ins.loading.show();
            } ,

            hide () {
                this.$refs.loading.ins.loading.hide();
            } ,
        }
    }
</script>

<style scoped>
    .view {
        position: relative;
        padding: 0 20px 20px 20px;
        height: 100%;
        overflow-y: auto;
    }

    .view .dynamic {
        background-color: white;
        padding: 20px;
    }
</style>