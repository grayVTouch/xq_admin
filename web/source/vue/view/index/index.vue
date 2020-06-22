<template>
    <div class="view">

        <!-- 焦点栏目 -->
        <div class="focus-bar">
            
            <div class="background"><img src="./res/background1.jpg" class="image"></div>

            <div class="content">
                <div class="slidebar" ref="slidebar">
                    <div class='pic-play-touch'>
                        <div class='images'>
                            <div class='_images'>
                                <a href='javascript:;' class='link'><img src='./res/slidebar/01.jpg' alt="" class='image'></a>
                                <a href='javascript:;' class='link'><img src='./res/slidebar/02.jpg' alt="" class='image'></a>
                                <a href='javascript:;' class='link'><img src='./res/slidebar/03.jpg' alt="" class='image'></a>
                            </div>

                            <div class='prev'><img src='../../../plugin/PicPlay_Touch/image/prev.png' alt="" class='image'></div>
                            <div class='next'><img src='../../../plugin/PicPlay_Touch/image/next.png' alt="" class='image'></div>
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
                            <button class="prev"><my-icon icon="prev01" /></button>
                            <button class="next"><my-icon icon="next01" /></button>
                        </div>
                    </div>
                </div>

                <div class="list">

                    <div class="item card-box" v-for="v in 10">
                        <!-- 封面 -->
                        <div class="thumb">
                            <img src="https://cdn.zzdaye.com/images/b/3/a/b3a1c940-025d-11ea-83d8-e7dea6ce9725.jpg" class="image">
                            <div class="mask">
                                <div class="top">
                                    <div class="type"><my-icon icon="zhuanyerenzheng" size="35" /></div>
                                    <div class="collection"><my-icon icon="shoucang2" size="35" /></div>
                                </div>
                                <div class="btm">
                                    <div class="count">40P</div>
                                </div>

                            </div>
                        </div>

                        <div class="introduction">
                            <!-- 标签 -->
                            <div class="tags">
                                <span class="ico"><my-icon icon="icontag" size="18" /></span>
                                <span class="tag">美女</span>
                                <span class="tag">大尺度</span>
                                <span class="tag">奶味</span>
                                <span class="tag">爆乳</span>
                                <span class="tag">蜜桃臀</span>
                                <span class="tag">酥胸</span>
                            </div>
                            <!-- 标题 -->
                            <div class="title">可爱人妻糯美子童颜巨乳等你品尝</div>
                            <!-- 发布者 -->
                            <div class="user">
                                <span class="avatar-outer"><img src="./res/logo.png" alt="" class="image avatar"></span>
                                <a class="name">real_yami</a>
                            </div>
                            <!-- 统计信息 -->
                            <div class="info">
                                <div class="left"><my-icon icon="shijian" class="ico" mode="right" /> 2个月前</div>
                                <div class="right">
                                    <span><my-icon icon="chakan" mode="right" />10</span>
                                    <span><my-icon icon="shoucang" mode="right" />100</span>
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