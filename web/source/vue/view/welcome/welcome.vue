<template>
    <main class="main" data-app="true">
        <section class="center" ref="center">

            <header class="header">
                <div class="logo">
                    <div class="inner"><img :src="$store.state.context.res.logo" class="image" alt=""></div>
                </div>
                <div class="site">兴趣部落</div>
            </header>

            <!-- 模块选择 -->
            <nav class="nav">
                <h2 class="title">请选择模块</h2>
                <div class="module">
                    <ul class="list" v-if="!val.pending.loading">
                        <li v-for="v in module" class="item"><my-link class="link" @click="toHome(v)">{{ v.name }}</my-link></li>
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
                        loading: false ,
                    } ,
                    module: false ,
                    // 模块显示
                    showModule: false ,
                } ,
                dom: {} ,
                module: [] ,
                once: true ,
            };
        } ,

        beforeRouteEnter (to , from , next) {
            next((vm) => {
                if (vm.once) {
                    vm.once = false;
                } else {
                    vm.getModuleData();
                }
            });
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
                this.pending('loading' , true);
                Api.welcome.module((msg , data , code) => {
                    this.pending('loading' , false);
                    if (code !== TopContext.code.Success) {
                        this.message(msg);
                        return ;
                    }
                    this.module = data;
                });
            } ,

            toHome (v) {
                G.s.json('module' , v);
                // this.pending('loading' , true);
                window.setTimeout(() => {
                    this.push({name: 'index'});
                } , 200);
            } ,

        } ,
    }
</script>

<style scoped src="./css/welcome.css"></style>