<template>
    <main class="main">
        <!-- 头部 -->
        <header class="header">

            <!-- 导航栏 -->
            <div class="navigation" :class="{fixed: val.fixed}">
                <nav class="nav nav-top">

                    <div class="item logo">

                        <div class="thing __logo__"><img src="./res/logo.png" class="image"></div>
                        <div class="thing menu"><img src="./res/menu.png" class="image"></div>
                        <div class="thing website"><a class="link">兴趣部落</a></div>

                    </div>

                    <div class="item search">
                        <div class="inner">
                            <div class="ico"><i class="run-iconfont run-iconfont-search"></i></div>
                            <div class="input"><input type="text" placeholder="搜索" class="form-input"></div>
                            <div class="type" :class="{show: val.navTypeList}">
                                <div class="current" @click.stop="showNavTypeList">{{ val.mime.value }}<i class="iconfont run-iconfont run-iconfont-arrow"></i></div>
                                <ul class="list hide" ref="nav-type-list" @click.stop>
                                    <li v-for="(v,k) in mimeRange" :key="v" :ref="'mime-item-' + k" :data-mime="k" @click="switchSearchType(k,v)">{{ v }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="item action">

                        <!-- 未登录状态 -->
                        <div class="login-layer">
                            <button class="login">登录</button>
                            <button class="register">快速注册</button>
                        </div>

                        <div class="login-layer-below-640">
                            <div class="login"><img src="./res/login.png" alt="" class="image"></div>
                        </div>

                        <!-- 登录状态 -->
                        <div class="logged-layer hide">
                            <div class="history">足迹</div>
                            <div class="collection">收藏</div>
                            <div class="info">个人信息</div>
                        </div>
                    </div>

                </nav>
                <nav class="nav nav-btm">
                    <div class="item left menu" ref="nav-menu">
                        <my-nav-menu :data="category"></my-nav-menu>
                    </div>
                    <div class="item right action"></div>
                </nav>
            </div>

        </header>

        <!-- 公告 -->
        <section class="announcement"></section>

        <!-- 内容 -->
        <section class="body" ref="body">
            <router-view></router-view>
        </section>
        <!-- 尾部 -->
        <footer class="footer"></footer>

        <!-- 导航菜单 -->
        <div class="main-nav">

        </div>
    </main>
</template>

<script src="./js/home.js"></script>

<style src="./css/global.css"></style>
<style scoped src="./css/home.css"></style>
<style scoped src="./css/home_below_640.css"></style>