<template>
    <main class="main">
        <!-- 头部 -->
        <header class="header" :class="{fixed: val.fixed}">
            <!--  -->
            <nav class="nav nav-top">
                <div class="item left logo">
                    <div class="line"><img src="./res/background1.jpg" class="image"></div>
                    <div class="line"><a class="link">兴趣部落</a></div>
                    <div class="line"><a class="link">公告</a></div>
                    <div class="line"><a class="link">站务</a></div>
                </div>

                <div class="item search">
                    <div class="inner">
                        <div class="ico"><i class="run-iconfont run-iconfont-search"></i></div>
                        <div class="input"><input type="text" placeholder="搜索" class="form-input"></div>
                        <div class="type">
                            <div class="current" ref="nav-type-current">图片<i class="run-iconfont run-iconfont-arrow"></i></div>
                            <ul class="list" ref="nav-type-list" v-show="val.navTypeList">
                                <li>图片</li>
                                <li>视频</li>
                                <li>资讯</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item right action">
                    <div class="line">登录</div>
                    <div class="line">注册</div>
                </div>

            </nav>
            <nav class="nav nav-btm">
                <div class="item left menu">
                    <div class="line">首页</div>
                    <div class="line">二次元</div>
                    <div class="line">三次元</div>
                </div>
                <div class="item right action"></div>
            </nav>
        </header>

        <!-- 公告 -->
        <section class="announcement"></section>

        <!-- 内容 -->
        <section class="body">
            <router-view></router-view>
        </section>
        <!-- 尾部 -->
        <footer class="footer"></footer>
    </main>
</template>

<script>
    export default {
        name: "home" ,
        data () {
            return {
                val: {
                    fixed: false ,
                    navTypeList: false ,
                }
            };
        } ,
    }
</script>


<style scoped src="./css/index.css"></style>