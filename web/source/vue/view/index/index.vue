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

        <!-- 分类内容 -->
        <div class="category">
            <!-- 最新上传 -->
            <div class="newest"></div>

            <!-- 图片 -->
            <div class="image">

            </div>
        </div>
    </div>
</template>

<script src="./js/index.js"></script>


<style scoped src="./css/index.css"></style>
<style scoped src="./css/index_below_640.css"></style>