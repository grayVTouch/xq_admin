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

                    <div class="pic-play-transform">
                        <div class="images">
                            <a class="link" v-for="v in homeSlideshow" :href='v.link'><img :src="v.__path__" alt="" class="image"></a>
                        </div>
                        <div class="index"></div>
                        <div class="action prev"><i class="run-iconfont run-iconfont-prev01"></i></div>
                        <div class="action next"><i class="run-iconfont run-iconfont-next01"></i></div>
                    </div>

                </div>

                <div class="box">
                    <div class="inner">

                        <div class="item" v-for="(v,k) in hotImages" v-if="k < 6">
                            <img :src="v.thumb ? v.__thumb__ : $store.state.context.res.notFound" alt="" class="image">
                            <div class="info">
                                <h5 class="title">{{ v.name  }}</h5>
                                <p class="desc">{{ v.desc }}</p>
                            </div>
                        </div>

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
                            <button class="tag" :class="{cur: group.image.curTag === 'newest'}" @click="newestInImageSubject">最新</button>
                            <button class="tag" :class="{cur: group.image.curTag === 'hot'}" @click="getHotImageSubject">热门</button>
                            <button class="tag" v-for="v in group.image.tag" :class="{cur: group.image.curTag === 'tag_' + v.tag_id}" @click="getImageByTagId(v.tag_id)">{{ v.name }}</button>
                            <button class="tag" @click="push('/image_subject/index')">更多</button>
                        </div>
                        <div class="operation">
                            <button class="prev" :class="{disabled: group.image.action.translateX === group.image.action.maxTranslateX}" @click="prevByGroup('image')"><my-icon icon="prev01" /></button>
                            <button class="next" :class="{disabled: group.image.action.translateX === group.image.action.minTranslateX}" @click="nextByGroup('image')"><my-icon icon="next01" /></button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-image">
                    <div class="loading" v-if="val.pending.images"><my-loading width="50" height="50"></my-loading></div>
                    <div class="inner" ref="inner-for-image">
                        <div class="item card-box" v-for="v in images">
                            <!-- 封面 -->
                            <div class="thumb" @click.stop="link(`#/image_subject/${v.id}/show`)">
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

                                    <span class="tag" v-for="tag in v.tags">{{ tag.name }}</span>
                                </div>
                                <!-- 标题 -->
                                <div class="title"  @click.stop="link(`#/image_subject/${v.id}/show`)">{{ v.name }}</div>
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
<style scoped src="../public/css/base.css"></style>
<style scoped src="./css/index.css"></style>