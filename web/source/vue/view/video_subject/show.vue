<template>
    <div class="view">

        <div class="left info">
            <div class="video-container" ref="video-container">
                <my-video ref="my-video"></my-video>
            </div>

            <div class="play-info">
                <div class="info">
                    <div class="name">听爸爸的话 01</div>
                    <div class="statistics">
                        <span class="praise"><my-icon icon="shoucang2" class="run-position-relative run-t--1" />100</span>&nbsp;|&nbsp;<span class="collect"><my-icon icon="shoucang5" class="run-position-relative run-t--1" />200</span>&nbsp;|&nbsp<span class="view-count"><my-icon icon="chakan" class="run-position-relative run-t--1" /> 300</span>&nbsp;|&nbsp;<span class="create-time"><my-icon icon="shijian" class="run-position-relative run-t--1" /> 2020-01-25 15:00:15</span>
                    </div>
                </div>
                <div class="actions">
                    <div class="action praise run-red"><my-icon icon="shoucang2" size="24"></my-icon>100</div>
<!--                    <div class="action hate"><my-icon icon="shoucang2" size="24"></my-icon></div>-->
                    <div class="action collect"><my-icon icon="shoucang5" size="24"></my-icon>200</div>
                </div>
            </div>

            <div class="video-subject">
                <div class="thumb"><img src="http://res.xq.test/upload/20200822/20200822112112FbbqUS.jpeg" v-judge-img-size class="image judge-img-size"></div>
                <div class="info">

                    <div class="two-column-layout core">
                        <div class="title">
                            <div class="name">听爸爸的话</div>
                            <div class="info">
                                <div class="statistics-1">4792.9万播放  ·  234.9万弹幕  ·  119.6万系列追番</div>
                                <div class="statistics-2">番剧已完结, 全167话</div>
                            </div>
                        </div>
                        <div class="item company">
                            <div class="field">视频制作公司</div>
                            <div class="value">Pink Pineapple</div>
                        </div>
                    </div>

                    <div class="two-column-layout time">
                        <div class="item release-time">
                            <div class="field">发布时间</div>
                            <div class="value">2020-02-15 15:00:33</div>
                        </div>
                        <div class="item end-time">
                            <div class="field">完结时间</div>
                            <div class="value">2020-02-15 15:00:33</div>
                        </div>
                        <div class="item upload-time">
                            <div class="field">上传时间</div>
                            <div class="value">2020-02-15 15:00:33</div>
                        </div>
                    </div>

                    <div class="desc">《犬夜叉》是根据日本漫画家高桥留美子所著同名作品改编的电视动画，故事讲述半妖少年——犬夜叉和穿越时空的少女——日暮戈薇，以及法师弥勒、驱魔师珊瑚、叉尾妖猫云母、小狐妖七宝一同对抗死敌奈落，并四处寻找四魂之玉碎片的冒险经历。</div>
                    <div class="item run-tags">
                        <my-link class="tag border-tag" target="_blank" v-for="v in 5">名称</my-link>
                    </div>
                </div>
                <div class="mark">
                    <div class="flag">UNCENSORED</div>
                    <div class="score">9.9</div>
                </div>
            </div>

            <div class="comments"></div>

        </div>

        <div class="right recommend">
            <div class="video-index">

                <!-- 索引标题 -->
                <div class="title">选集</div>

                <!-- 索引范围 -->
                <div class="index-range run-space-between">
                    <div class="left">
                        <div class="item cur">1-30</div>
                        <div class="item">31-60</div>
                        <div class="item">61-90</div>
                        <div class="item">91-120</div>
                    </div>
                    <div class="right">
                        <div class="item more">加载更多<my-icon icon="arrow"></my-icon>
                        </div>
                    </div>
                    <div class="more-index hide">
                        <div class="item">121-150</div>
                        <div class="item">151-180</div>
                        <div class="item">181-210</div>
                        <div class="item">211-240</div>
                        <div class="item">241-270</div>
                        <div class="item">121-150</div>
                        <div class="item">151-180</div>
                        <div class="item">181-210</div>
                        <div class="item">211-240</div>
                        <div class="item">241-270</div>
                    </div>
                </div>

                <!-- 索引 -->
                <div class="indexs">

                    <div class="item" v-for="v in 30">
                        <div class="thumb">
                            <div class="image-mask"><img src="http://res.xq.test/upload/20200822/20200822112112FbbqUS.jpeg" v-judge-img-size class="image judge-img-size"></div>
                        </div>
                        <div class="info">
                            <div class="name">无暇之夜</div>
                            <div class="subject">Pink Pineapple</div>
                            <div class="statistics">1000次观看</div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="video-series">
                <div class="title">系列视频</div>
                <div class="list">

                    <div class="series" v-for="v in 3">
                        <div class="thumb"><img src="http://res.xq.test/upload/20200822/20200822112112FbbqUS.jpeg" class="judge-img-size" v-judge-img-size alt=""></div>
                        <div class="info">
                            <div class="name">犬夜叉 完结版</div>
                            <div class="statistics">
                                <div class="index-count">全26话</div>
                                <div class="statistics">100播放</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="relation"></div>
        </div>
    </div>
</template>

<script src="./js/show.js"></script>

<style src="../public/css/base.css"></style>
<style scoped src="./css/show.css"></style>