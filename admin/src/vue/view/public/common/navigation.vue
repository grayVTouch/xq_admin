<template>
    <div class="navigation">
        <!-- 导航内容 -->
        <div class="nav" :class="{fixed: fixed}" ref="nav">
            <div class="left">
                <img :src="topRoute.bIco" class="image" alt="">
                <span class="cn">{{ topRoute.cn }}</span>
                <span class="delimiter">/</span>
                <span class="en">{{ topRoute.en }}</span>
                &nbsp;&nbsp;
<!--                <button v-ripple class="run-button run-button-blue" @click.prevent="reload()">-->
<!--                    <i class="run-iconfont run-reset"></i>标签页刷新-->
<!--                </button>-->

            </div>
            <div class="right">
                <!-- 面包屑 -->
                <template v-for="(v,k) in position">
                    <span v-ripple class="text" :class="{'run-cursor-not-allow':  !v.view }" @click="open(v)">{{ v.cn }}</span>
                    <span class="delimiter" v-if="!(k == position.length - 1)">/</span>
                </template>
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
                value: {} ,
            };
        } ,
        props: {
            topRoute: {
                type: Object ,
                required: true ,
                default () {
                    return {};
                } ,
            } ,
            position: {
                type: Array ,
                required: true ,
                default () {
                    return [];
                } ,
            }
        } ,
        mounted () {
            this.initDom();
            this.init();
        } ,
        methods: {
            initDom () {
                this.dom.root = G(this.$el);
                this.dom.win = G(window);
                this.dom.scrollContainer = G(this.$parent.$el);
            } ,

            init () {
                this.value.navH = this.dom.root.height('content-box');
                this.fixNav();
                // this.dom.win.on('scroll' , this.fixNav.bind(this) , true , false);

                this.dom.scrollContainer.on('scroll' , this.fixNav.bind(this) , true , false);
            } ,

            fixNav () {
                // let y = window.pageYOffset;
                let y = this.dom.scrollContainer.scrollTop();
                console.log();
                if (0 <= y && y <= this.value.navH) {
                    this.fixed = false;
                } else {
                    this.fixed = true;
                }
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
<style scoped src="../css/navigation.css"></style>
