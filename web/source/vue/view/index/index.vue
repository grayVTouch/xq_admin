<template>
    <div class="view">

        <!-- 焦点栏目 -->
        <div class="focus-bar">
            
            <div class="background">

                <div class="mask"></div>
                <div class="bg-image" :style="'background-image: url(\'' + background.image + '\')'"></div>


            </div>

            <div class="content">
                <div class="slidebar" ref="slidebar">
                    <div class='pic-play-touch'>
                        <div class='images'>
                            <div class='_images'>

                                <a v-for="v in homeSlideshow" :href='v.link' class='link'><img :src='v.__path__' alt="" class='image'></a>
<!--                                <a href='javascript:;' class='link'><img src='./res/slidebar/02.jpg' alt="" class='image'></a>-->
<!--                                <a href='javascript:;' class='link'><img src='./res/slidebar/03.jpg' alt="" class='image'></a>-->

                            </div>

                            <div class='prev'>
<!--                                <img src='../../../plugin/PicPlay_Touch/image/prev.png' alt="" class='image'>-->
                                <i class="run-iconfont run-iconfont-prev01"></i>
                            </div>
                            <div class='next'>
<!--                                <img src='../../../plugin/PicPlay_Touch/image/next.png' alt="" class='image'>-->
                                <i class="run-iconfont run-iconfont-next01"></i>
                            </div>
                        </div>

                        <div class="index">
                            <div class='_index hide'></div>
                            <div class='_image hide'></div>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="inner">
                        <div class="item">
                            <img src="./res/logo.jpg" class="image">
                            <div class="info">
                                <h5 class="title">【COS正片】明日方舟黑cos 甜美动人 cn周叽是可爱兔兔</h5>
                                <p class="desc">介绍：不喜欢与他人交流。即使有人想与她交流，通常也只会得到比较冷淡的回应。 不过，偶尔也会</p>
                            </div>
                        </div>
                        <div class="item"><img src="./res/logo.jpg" class="image"></div>
                        <div class="item"><img src="./res/logo.jpg" class="image"></div>
                        <div class="item"><img src="./res/logo.jpg" class="image"></div>
                        <div class="item"><img src="./res/logo.jpg" class="image"></div>
                        <div class="item"><img src="./res/logo.jpg" class="image"></div>

                        <div class="item"><img src="./res/logo.jpg" class="image"></div>
                        <div class="item"><img src="./res/logo.jpg" class="image"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 内容分组 -->
        <div class="content-group">

            <!-- 图片 -->
            <div class="group group-for-image">
                <!-- 导航 -->
                <div class="run-action-title">
                    <div class="left">图片</div>
                    <div class="right">
                        <div class="tags">
                            <a class="tag">最新</a>
                            <a class="tag">性感</a>
                            <a class="tag">活力</a>
                            <a class="tag">少女</a>
                            <a class="tag">全部</a>
                        </div>
                        <div class="operation">
                            <button class="prev" @click="prevByGroup('image')"><my-icon icon="prev01" /></button>
                            <button class="next" @click="nextByGroup('image')"><my-icon icon="next01" /></button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-image">
                    <div class="inner" ref="inner-for-image">
                        <div class="item card-box" v-for="v in images">
                            <!-- 封面 -->
                            <div class="thumb">
                                <img :src="v.__thumb__" class="image">
                                <div class="mask">
                                    <div class="top">
                                        <div class="type" v-if="v.type === 'pro'"><my-icon icon="zhuanyerenzheng" size="35" /></div>
                                        <div class="collection"><my-icon icon="shoucang2" size="35" /></div>
                                    </div>
                                    <div class="btm">
                                        <div class="count">{{ v.images.length }}P</div>
                                    </div>

                                </div>
                            </div>

                            <div class="introduction">
                                <!-- 标签 -->
                                <div class="tags">
                                    <span class="ico"><my-icon icon="icontag" size="18" /></span>

                                    <span class="tag" v-for="tag in v.__tag__">{{ tag.name }}</span>
                                </div>
                                <!-- 标题 -->
                                <div class="title">{{ v.name }}</div>
                                <!-- 发布者 -->
                                <div class="user">
                                    <template v-if="v.user">
                                        <span class="avatar-outer"><img :src="v.user.__avatar__" alt="" class="image avatar"></span>
                                        <a class="name">{{ v.user.username }}</a>
                                    </template>
                                    <template v-else>
                                        <span class="avatar-outer"><img src="./res/logo.png" alt="" class="image avatar"></span>
                                        <a class="name">real_yami</a>
                                    </template>
                                </div>
                                <!-- 统计信息 -->
                                <div class="info">
                                    <div class="left"><my-icon icon="shijian" class="ico" mode="right" /> {{ v.update_time }}</div>
                                    <div class="right">
                                        <span><my-icon icon="chakan" mode="right" />{{ v.view_count }}</span>
                                        <span><my-icon icon="shoucang" mode="right" />{{ v.praise_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>


    </div>
</template>

<script src="./js/index.js"></script>

<style scoped src="./css/index.css"></style>