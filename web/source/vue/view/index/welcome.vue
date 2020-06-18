<template>
    <main class="main">
        <section class="center" ref="center">

            <header class="header">
                <div class="logo"><img src="./res/logo.jpg" class="image" alt=""></div>
                <div class="site">兴趣部落</div>
            </header>

            <!-- 模块选择 -->
            <nav class="nav">
                <h2 class="title">请选择模块</h2>
                <ul class="list">
                    <li class="item"><router-link :to="{name: 'home'}" class="link">新世界</router-link></li>
                    <li class="item"><router-link :to="{name: 'home'}" class="link">旧世界</router-link></li>
                </ul>
            </nav>

        </section>
    </main>
</template>

<script>
    export default {
        name: "welcome" ,

        data () {
            return {
                val: {
                    show: false ,
                } ,
                dom: {} ,
            };
        } ,

        mounted () {
            this.initDom();
            this.initStyle();
        } ,

        methods: {
            initDom () {
                this.dom.center = G(this.$refs.center);
            } ,

            initStyle () {
                // 显示动画
                this.dom.center.startTransition('show')
            } ,
        } ,
    }
</script>


<style scoped src="./css/welcome.css"></style>