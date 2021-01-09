<template>
    <div class="navigation">
            <div ref="nav"
                 class="nav"
                 :class="{fixed: fixed , spread: state().slidebar === 'vertical'}"
            >
            <div class="inner">
                <div class="left">
                    <img :src="state().topRoute.bIco" class="image" alt="">
                    <span class="cn">{{ state().topRoute.cn }}</span>
                    <span class="delimiter">/</span>
                    <span class="en">{{ state().topRoute.en }}</span>
                </div>
                <div class="right">
                    <!-- 面包屑 -->
                    <template v-for="(v,k) in state().positions">
                        <span v-ripple class="text" :class="{'run-cursor-not-allow':  !v.view }" @click="open(v)">{{ v.cn }}</span>
                        <span class="delimiter" v-if="!(k == state().positions.length - 1)">/</span>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "navigation" ,
        data () {
            return {
                dom: {} ,
                instance: {} ,
                fixed: false ,
                myValue: {} ,
            };
        } ,

        mounted () {
            this.initDom();
            this.initValue();
            this.init();
        } ,
        methods: {
            initDom () {
                this.dom.root = G(this.$el);
                this.dom.win = G(window);
                this.dom.scrollContainer = G(this.$parent.$refs['view']);
            } ,

            initValue () {

            } ,

            init () {
                this.myValue.navH = this.dom.root.height('content-box');
                this.fixNav();
                this.dom.scrollContainer.on('scroll' , this.fixNav.bind(this) , true , false);
            } ,

            fixNav () {
                let y = this.dom.scrollContainer.scrollTop();
                this.fixed = !(0 <= y && y < this.myValue.navH);
            } ,

            // 打开新标签页
            open (route) {
                if (route.enable && route.is_view) {
                    this.$root.location(route.value);
                }
            } ,

            // 重载标签页
            reload () {
                this.$root.reload();
            } ,


        }
    }
</script>
<style scoped src="./css/navigation.css"></style>
