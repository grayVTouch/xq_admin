<template>
    <main class="main" data-app="true">
        <section class="center" ref="center">

            <header class="header">
                <div class="logo">
                    <div class="inner"><img src="./res/logo.jpg" class="image" alt=""></div>
                </div>
                <div class="site">兴趣部落</div>
            </header>

            <!-- 模块选择 -->
            <nav class="nav">
                <h2 class="title">请选择模块</h2>
                <div class="module">
                    <ul class="list" v-if="!val.pending.module">
                        <li v-for="v in module" class="item"><a class="link" @click="toHome(v)">{{ v.name }}</a></li>
                    </ul>
                    <div class="loading" v-else>
                        <my-loading></my-loading>
                    </div>
                </div>
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
                    pending: {
                        module: false ,
                    } ,
                    module: false ,
                    // 模块显示
                    showModule: false ,
                } ,
                dom: {} ,
                module: [] ,
            };
        } ,

        mounted () {
            this.initDom();
            this.showModule();
            this.getModuleData();
        } ,

        methods: {
            initDom () {
                this.dom.center = G(this.$refs.center);
            } ,

            showModule () {
                this.dom.center.startTransition('show');
            } ,

            hideModule () {
                this.dom.center.endTransition('show');
            } ,

            getModuleData () {
                this.pending('module' , true);
                Api.module.all((data , code) => {
                    this.pending('module' , false);
                    if (code !== TopContext.code.Success) {
                        this.message(data);
                        return ;
                    }
                    this.module = data;
                });
            } ,

            toHome (v) {
                G.s.json('module' , v);
                this.push({name: 'index'});
            } ,

        } ,
    }
</script>

<style src="./css/global.css"></style>
<style scoped src="./css/welcome.css"></style>
<style scoped src="./css/welcome_below_640.css"></style>