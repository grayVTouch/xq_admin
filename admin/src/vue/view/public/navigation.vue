<template>
    <div class="navigation">
        <!-- 导航内容 -->
        <div ref="nav"
             class="nav"
             :class="{fixed: fixed , spread: state().slidebar === 'vertical'}"
             :style="`width: ${fixed ? 'calc(100% - ' + $parent.val.yScrollbarWidth + 'px)' : ''};right: ${fixed ? $parent.val.yScrollbarWidth + 'px' : '0'}`"
        >

            <div class="inner">
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
                val: {} ,
            };
        } ,
        props: {
            topRoute: {
                type: Object ,
                default () {
                    return {};
                } ,
            } ,
            position: {
                type: Array ,
                default () {
                    return [];
                } ,
            }
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
                this.dom.scrollContainer = G(this.$parent.$el);
            } ,

            initValue () {

            } ,

            init () {
                this.val.navH = this.dom.root.height('content-box');
                this.fixNav();
                // this.dom.win.on('scroll' , this.fixNav.bind(this) , true , false);
                this.dom.scrollContainer.on('scroll' , this.fixNav.bind(this) , true , false);
            } ,

            fixNav () {
                let y = this.dom.scrollContainer.scrollTop();
                this.fixed = !(0 <= y && y <= this.val.navH);
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
