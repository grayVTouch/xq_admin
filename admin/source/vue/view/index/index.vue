<template>
    <div class="main">
        <div class="menu" ref="menu">
            <div class="inner" ref="menu-inner">
                <div class="line logo" ref="logo">
                    <div class="c-left image-outer">
                        <div class="inner"><img :src="$store.state.context.res.logo" class="image"></div>
                    </div>
                    <div class="c-right text-outer">{{ $store.state.context.os.name }}</div>
                </div>

                <div class="line avatar" ref="avatar">
                    <div class="inner">
                        <div class="top">
                            <my-avatar
                                    :src="$store.state.user.__avatar__"
                                    :mask="true"
                                    :top-val="$store.state.user.username"
                                    :btm-val="$store.state.user.role ? $store.state.user.role.name : '无'"
                            ></my-avatar>
                        </div>
                        <div class="btm">running</div>
                    </div>
                </div>

                <div class="line block" ref="block">
                    <!-- 水平 -->
                    <div class="horizontal" ref="horizontal" @click="vertical">
                        <div class="in">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>

                    <!-- 垂直 -->
                    <div class="vertical hide" ref="vertical" @click="horizontal">
                        <div class="in">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>

                <div class="line menus" ref="menus">
                    <my-menu :list="$store.state.permissionWithStructure"></my-menu>
                </div>

                <div class="line desc" ref="desc">{{ $store.state.context.os.name }}</div>
            </div>
        </div>

        <div class="content" ref="content">
            <div class="inner">
                <div class="line nav" ref="nav">
                    <div class="top toolbar" ref="toolbar">

                        <div class="left">
                            <!-- 测试范例：翻译 -->
                            <div class="translate hide" title="这是用来生成常用中英文对照字典，加快前端展示的功能，请再更新数据后不定时调用此功能，以保证前端中英文流畅切换">
                                <img src="./res/translate.png" class="image">
                            </div>
                        </div>

                        <div class="right">

                            <!-- 测试范例：通知栏 -->
                            <div class="notification hide">
                                <div class="in">
                                    <div class="image-container">
                                        <img src="./res/notification.png" class="image">
                                    </div>
                                    <div class="text">10</div>
                                </div>
                            </div>

                            <!--用户控制-->
                            <div class="user" @mouseenter="showUserCtrl" @mouseleave="hideUserCtrl">
                                <div class="ctrl">
                                    <div class="avatar">
                                        <my-avatar :src="$store.state.user.__avatar__"></my-avatar>
                                    </div>
                                    <div class="username">{{ $store.state.user.username }}</div>
                                </div>
                                <div class="functions hide" ref="functions-for-user">
                                    <div class="function" @click="logout">
                                        <div class="left"><my-icon icon="084tuichu"></my-icon></div>
                                        <div class="right">注销</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="btm tabs" ref="tabs">
                        <!--多标签-->
                        <div class="multiple-tab">
                            <!-- 项列表 -->
                            <div class="tabs"></div>
                        </div>
                    </div>
                </div>

                <div class="line area" ref="area">
                    <div class="inner" ref="tab-items"></div>
                </div>

                <div class="line info hide" ref="info">{{ $store.state.context.os.name }}</div>
            </div>
        </div>
    </div>
</template>

<script src="./js/index.js"></script>
<style src="./css/index.css"></style>