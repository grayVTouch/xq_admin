<template>
    <div class="container">
        <div class="left menu">
            <div class="line logo">
                <div class="c-left image-outer">
                    <div class="inner"><img src="./res/logo.jpg" class="image"></div>
                </div>
                <div class="c-right text-outer">兴趣部落管理后台</div>
            </div>



            <div class="line avatar" ref="avatar">
                <div class="top">
                    <div class="outer"><img src="./res/logo.jpg" class="image"></div>
                </div>
                <div class="btm">running</div>
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

            <div class="line menus" ref="infinite-classification">
                <v-menu></v-menu>
            </div>

            <div class="line desc">兴趣部落管理后台</div>
        </div>

        <div class="right content">
            <div class="top nav">
                <div class="c-top toolbar"></div>
                <div class="c-btm tabs"></div>
            </div>
            <div class="btm area"></div>
        </div>
    </div>
</template>

<script src="./js/index.js"></script>
<style scoped src="./css/index.css"></style>