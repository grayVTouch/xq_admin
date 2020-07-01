<template>
    <div class="view">
        <div class="content">
            <!-- 详情 -->
            <div class="left image-subject" ref="image-subject">
                <div class="subject">
                    <div class="title run-action-title m-b-15">
                        <div class="left">{{ data.name }}</div>
                        <div class="right">

                            <button class="praise"><my-icon icon="shoucang2" /> 喜欢 {{ data.praise_count }}</button>
                        </div>
                    </div>

                    <div class="tags m-b-15">
                        <div class="left run-tags">
                            <span class="ico p-r-5"><my-icon icon="icontag" /></span>
                            <span class="tag" v-for="v in data.tags">{{ v.name }}</span>
                        </div>
                        <div class="right">
                            <span class="number">{{ data.images.length }}P</span>&nbsp;|&nbsp;<span class="view-count"><my-icon icon="chakan" /> {{ data.view_count }}</span>&nbsp;|&nbsp;<span class="create-time"><my-icon icon="shijian" /> {{ data.create_time }}</span>
                        </div>
                    </div>

                    <div class="desc m-b-30">{{ data.description }}</div>
                    <div class="images">
                        <img v-for="(v,k) in images.data" :src="v.__path__" @click="imageClick(k + 1)" class="image">
                    </div>
                </div>

                <div class="comment"></div>
            </div>

            <!-- 其他 -->
            <div class="right misc">
                <div class="inner" :class="{fixed: val.fixedMisc}">
                    <!-- 发布者 -->
                    <div class="user">
                        <div class="avatar">
                            <div class="mask">
                                <img :src="data.user ? data.user.__avatar__ : $store.state.context.res.notFound" class="image" alt="">
                            </div>
                        </div>
                        <div class="name">{{ data.user ? data.user.username : '' }}</div>
                        <div class="data">
                            <div class="left">关注 1</div>
                            <div class="right">粉丝 4106</div>
                        </div>
                        <div class="desc">个人简介</div>
                        <div class="action">
                            <button class="focus"><my-icon icon="add" /> 关注</button>
                            <button class="message">私信</button>
                        </div>
                    </div>
                    <!-- 关联主题 -->
                    <div class="subject" v-if="data.subject">
                        <div class="info">
                            <div class="thumb">
                                <div class="mask">
                                    <img :src="data.subject ? data.subject.__thumb__ : $store.state.context.res.notFound" class="image" alt="">
                                </div>
                            </div>
                            <div class="name">{{ data.subject.name }}</div>
                            <div class="desc">{{ data.subject.description }}</div>
                        </div>
                        <div class="attr" v-if="data.subject">
                            <!--                        <div class="title">简介</div>-->
                            <div class="list">
                                <div class="line" v-for="v in data.subject.__attr__">
                                    <span class="field">{{ v.field }}：</span>
                                    <span class="value">{{ v.value }}</span>
                                </div>
                            </div>
                        </div>
                        <!--                    <div class="action">-->
                        <!--                        <button class="focus"><my-icon icon="add" /> 关注</button>-->
                        <!--                    </div>-->

                    </div>
                    <!-- 推荐 -->
                    <div class="comment"></div>
                    <!-- 移动端 -->
                    <div class="mobile"></div>
                </div>
            </div>
        </div>

        <div class="loading" v-if="val.pending.getData">
            <my-loading width="50" height="50"></my-loading>
        </div>

        <!-- 图片预览 -->
        <div class="pic-preview-container" ref="pic-preview-container">
            <div class="pic-preview">
                <div class="preview">

                    <div class="header">
                        <div class="info">1 / 25</div>
                        <div class="action">
                            <button class="origin"><i class="run-iconfont run-iconfont-huanyuan"></i></button>
                            <button class="shrink"><i class="run-iconfont run-iconfont-suoxiao"></i></button>
                            <button class="grow"><i class="run-iconfont run-iconfont-fangda-copy"></i></button>
                            <button class="full-screen"><i class="run-iconfont run-iconfont-quanping1"></i></button>
                            <button class="normal-screen"><i class="run-iconfont run-iconfont-quanping"></i></button>
                            <button class="spread"><i class="run-iconfont run-iconfont-weibiaoti26"></i></button>
                            <button class="narrow"><i class="run-iconfont run-iconfont-daohangzhankai-"></i></button>
                            <button class="close"><i class="run-iconfont run-iconfont-close"></i></button>
                        </div>
                    </div>

                    <div class="content">
                        <div class="b-images">
                            <div class="item" v-for="v in data.images"><img :src="v.__path__" class="image" alt=""></div>
                        </div>
                        <div class="action prev"><i class="run-iconfont run-iconfont-houtui"></i></div>
                        <div class="action next"><i class="run-iconfont run-iconfont-qianjin"></i></div>
                    </div>
                </div>

                <div class="s-images">
                    <div class="title m-b-15">
                        <div class="name">图片列表</div>
                        <div class="action">
                            <button class="close"><i class="run-iconfont run-iconfont-close"></i></button>
                        </div>
                    </div>
                    <div class="list">
                        <!--                <div class="item cur"><img src="./res/01.jpg" class="image" alt=""></div>-->
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script src="./js/show.js"></script>
<style scoped src="../public/css/base.css"></style>
<style scoped src="./css/show.css"></style>