<template>
    <div class="main">
        <div class="menu" ref="menu">
            <div class="inner" ref="menu-inner">
                <div class="line logo" ref="logo">
                    <div class="c-left image-outer">
                        <div class="inner"><img :src="state().context.res.logo" class="image"></div>
                    </div>
                    <div class="c-right text-outer">{{ state().context.os.name }}</div>
                </div>

                <div class="line avatar" ref="avatar">
                    <div class="inner">
                        <div class="top">
                            <my-avatar
                                    :src="user().avatar"
                                    :mask="true"
                                    :top-val="user().username"
                                    :btm-val="user().role ? user().role.name : '无'"
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
                    <my-menu :data="$store.state.position"></my-menu>
                </div>

                <div class="line desc" ref="desc">{{ state().context.os.name }}</div>
            </div>
        </div>

        <div class="content" ref="content">
            <div class="inner">
                <div class="line nav" :style="`padding-right: ${val.yScrollbarWidth}px`" ref="nav">
                    <div class="top toolbar" ref="toolbar">

                        <div class="left">
                            <!-- 测试范例：翻译 -->
                            <Tooltip content="刷新当前标签页">
                                <div v-ripple @click="reloadChildPage">
                                    <my-icon icon="reset"></my-icon>
                                </div>
                            </Tooltip>
                            <Tooltip content="清空失败队列">
                                <div v-ripple @click="clearFailedJobs">
                                    <my-icon icon="qingkong"></my-icon>
                                </div>
                            </Tooltip>
                            <Tooltip content="重试失败队列">
                                <div v-ripple @click="retryFailedJobs">
                                    <my-icon icon="lianjie1"></my-icon>
                                </div>
                            </Tooltip>
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
                                        <my-avatar :src="user().avatar"></my-avatar>
                                    </div>
                                    <div class="username">{{ user().username }}</div>
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

                <div class="line view" ref="view">
                    <my-navigation ref="navigation"></my-navigation>
                    <div class="dynamic" ref="dynamic">
                        <router-view></router-view>
                    </div>
                    <div class="to-top hide">
                        <img src="" class="image" />
                    </div>
                </div>

                <div class="line info hide" ref="info">{{ state().context.os.name }}</div>
            </div>
        </div>
    </div>
</template>

<script src="./js/index.js"></script>
<style src="./css/index.css"></style>
