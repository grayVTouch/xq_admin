<template>
    <main class="main">
        <!-- 头部 -->
        <header class="header">

            <!-- 导航栏 -->
            <div class="navigation" :class="{fixed: val.fixed}">
                <nav class="nav nav-top">
                    <div class="inner">
                        <div class="logo">

                            <div class="__logo__"><img src="./res/logo.png" class="image"></div>
                            <div class="site"><a class="link">兴趣部落</a></div>

                        </div>

                        <div class="search search-in-logged">
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

                        <div class="action">

                            <!-- 未登录状态 -->
                            <div class="login-layer">
                                <button class="login">登录</button>
                                <button class="register">注册</button>
                            </div>

                            <!-- 登录状态 -->
                            <div class="logged-layer hide">
                                <div class="history">足迹</div>
                                <div class="collection">收藏</div>
                                <div class="info">个人信息</div>
                            </div>
                        </div>
                    </div>
                </nav>
                <nav class="nav nav-btm">
                    <div class="inner">
                        <div class="item left menu" ref="nav-menu">
                            <my-nav-menu :data="category"></my-nav-menu>
                        </div>
                        <div class="item right action"></div>
                    </div>
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
        <footer class="footer">
            <p class="website">兴趣部落</p>
            <p class="links">
                友情链接：
                <a href="https://awm.moe/">ACG萌图</a>
                <a href="https://hanime.tv/">hanime</a>
            </p>
            <p class="copyright">Copyright©2003-2020 xq.com All rights reserved. 兴趣部落 版权所有</p>
        </footer>

        <!-- 导航菜单 -->
        <div class="main-nav hide">

        </div>
    </main>
</template>

<script src="./js/home.js"></script>

<style src="./css/global.css"></style>
<style scoped src="./css/home.css"></style>