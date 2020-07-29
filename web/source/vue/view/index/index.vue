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
                            <a class="link" v-for="v in homeSlideshow" :key="v.id" :href='v.link'><img :src="v.__path__" alt="" v-judge-img-size class="image judge-img-size"></a>
                        </div>
                        <div class="index"></div>
                        <div class="action prev"><i class="run-iconfont run-iconfont-prev01"></i></div>
                        <div class="action next"><i class="run-iconfont run-iconfont-next01"></i></div>
                    </div>

                </div>

                <div class="box">
                    <div class="inner">

                        <a class="item" v-for="(v,k) in hotImages" :key="v.id" v-if="k < 6" target="_blank" :href="`#/image_subject/${v.id}/show`">
                            <img :src="v.thumb ? v.__thumb__ : $store.state.context.res.notFound" alt="" v-judge-img-size class="image judge-img-size">
                            <div class="info">
                                <h5 class="title">{{ v.name  }}</h5>
                                <p class="desc">{{ v.description }}</p>
                            </div>
                        </a>

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
                            <my-button class="tag" :class="{cur: group.image.curTag === 'newest'}" @click="newestInImageSubject">最新</my-button>
                            <my-button class="tag" :class="{cur: group.image.curTag === 'hot'}" @click="getHotImageSubject">热门</my-button>
                            <my-button class="tag" v-for="v in group.image.tag" :key="v.id" :class="{cur: group.image.curTag === 'tag_' + v.tag_id}" @click="getImageByTagId(v.tag_id)">{{ v.name }}</my-button>
                            <my-link class="tag" href="#/image_subject/index">更多</my-link>
                        </div>
                        <div class="operation">
                            <my-button class="prev" :class="{disabled: group.image.action.translateX === group.image.action.maxTranslateX}" @click="prevByGroup('image')"><my-icon icon="prev01" /></my-button>
                            <my-button class="next" :class="{disabled: group.image.action.translateX === group.image.action.minTranslateX}" @click="nextByGroup('image')"><my-icon icon="next01" /></my-button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-image">
                    <div class="loading" v-if="val.pending.images"><my-loading width="50" height="50"></my-loading></div>
                    <div class="inner" ref="inner-for-image">
                        <div class="item card-box" v-for="v in images" :key="v.id">
                            <!-- 封面 -->
                            <div class="thumb">
                                <a class="link" target="_blank" :href="`#/image_subject/${v.id}/show`">
                                    <img :src="v.thumb ? v.__thumb__ : $store.state.context.res.notFound" v-judge-img-size class="image judge-img-size">
                                    <div class="mask">
                                        <div class="top">
                                            <div class="type" v-if="v.type === 'pro'"><my-icon icon="zhuanyerenzheng" size="35" /></div>
                                            <div class="praise" v-ripple @click.prevent="praiseImageSubjectByImageSubject(v)">
                                                <my-loading size="16" v-if="val.pending.praiseImageSubjectByImageSubject"></my-loading>
                                                <my-icon icon="shoucang2" :class="{'run-red': v.praised }" /> 喜欢
                                            </div>
                                        </div>
                                        <div class="btm">
                                            <div class="count">{{ v.images.length }}P</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="introduction">
                                <!-- 标签 -->
                                <div class="tags">
                                    <span class="ico"><my-icon icon="icontag" size="18" /></span>

                                    <a class="tag" target="_blank" v-for="tag in v.tags" :href="`#/image_subject/search?tag_id=${tag.tag_id}`">{{ tag.name }}</a>
                                </div>
                                <!-- 标题 -->
                                <div class="title"><a target="_blank" :href="`#/image_subject/${v.id}/show`">{{ v.name }}</a></div>
                                <!-- 发布者 -->
                                <div class="user">
                                    <div class="sender">
                                        <span class="avatar-outer"><img :src="v.user.avatar ? v.user.__avatar__ : $store.state.context.res.avatar" alt="" class="image avatar"></span>
                                        <a class="name">{{ v.user.nickname }}</a>
                                    </div>
                                    <div class="action"></div>
                                </div>
                                <!-- 统计信息 -->
                                <div class="info">
                                    <div class="left"><my-icon icon="shijian" class="ico" mode="right" /> {{ v.create_time }}</div>
                                    <div class="right">
                                        <span class="view-count"><my-icon icon="chakan" mode="right" />{{ v.view_count }}</span>
                                        <span class="praise-count"><my-icon icon="shoucang2" mode="right" />{{ v.praise_count }}</span>
                                        <span class="collect-count" v-if="$store.state.user"><my-icon icon="shoucang6" mode="right" />{{ v.collect_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- 视频 -->
            <div class="group group-for-video">
                <!-- 导航 -->
                <div class="run-action-title">
                    <div class="left">视频</div>
                    <div class="right">
                        <div class="tags">
                            <my-button class="tag" :class="{cur: group.image.curTag === 'newest'}" @click="newestInImageSubject">最新</my-button>
                            <my-button class="tag" :class="{cur: group.image.curTag === 'hot'}" @click="getHotImageSubject">热门</my-button>
                            <my-button class="tag" v-for="v in group.image.tag" :key="v.id" :class="{cur: group.image.curTag === 'tag_' + v.tag_id}" @click="getImageByTagId(v.tag_id)">{{ v.name }}</my-button>
                            <my-link class="tag" href="#/image_subject/index">更多</my-link>
                        </div>
                        <div class="operation">
                            <my-button class="prev" :class="{disabled: group.image.action.translateX === group.image.action.maxTranslateX}" @click="prevByGroup('image')"><my-icon icon="prev01" /></my-button>
                            <my-button class="next" :class="{disabled: group.image.action.translateX === group.image.action.minTranslateX}" @click="nextByGroup('image')"><my-icon icon="next01" /></my-button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-image">
                    <div class="loading" v-if="val.pending.images"><my-loading width="50" height="50"></my-loading></div>
                    <div class="inner" ref="inner-for-image">
                        <div class="item card-box" v-for="v in images" :key="v.id">
                            <!-- 封面 -->
                            <div class="thumb">
                                <a class="link" target="_blank" :href="`#/image_subject/${v.id}/show`">
                                    <img :src="v.thumb ? v.__thumb__ : $store.state.context.res.notFound" v-judge-img-size class="image judge-img-size">
                                    <div class="mask">
                                        <div class="top">
                                            <div class="type" v-if="v.type === 'pro'"><my-icon icon="zhuanyerenzheng" size="35" /></div>
                                            <div class="praise" v-ripple @click.prevent="praiseImageSubjectByImageSubject(v)">
                                                <my-loading size="16" v-if="val.pending.praiseImageSubjectByImageSubject"></my-loading>
                                                <my-icon icon="shoucang2" :class="{'run-red': v.praised }" /> 喜欢
                                            </div>
                                        </div>
                                        <div class="btm">
                                            <div class="count">{{ v.images.length }}P</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="introduction">
                                <!-- 标签 -->
                                <div class="tags">
                                    <span class="ico"><my-icon icon="icontag" size="18" /></span>

                                    <a class="tag" target="_blank" v-for="tag in v.tags" :href="`#/image_subject/search?tag_id=${tag.tag_id}`">{{ tag.name }}</a>
                                </div>
                                <!-- 标题 -->
                                <div class="title"><a target="_blank" :href="`#/image_subject/${v.id}/show`">{{ v.name }}</a></div>
                                <!-- 发布者 -->
                                <div class="user">
                                    <div class="sender">
                                        <span class="avatar-outer"><img :src="v.user.avatar ? v.user.__avatar__ : $store.state.context.res.avatar" alt="" class="image avatar"></span>
                                        <a class="name">{{ v.user.nickname }}</a>
                                    </div>
                                    <div class="action"></div>
                                </div>
                                <!-- 统计信息 -->
                                <div class="info">
                                    <div class="left"><my-icon icon="shijian" class="ico" mode="right" /> {{ v.create_time }}</div>
                                    <div class="right">
                                        <span class="view-count"><my-icon icon="chakan" mode="right" />{{ v.view_count }}</span>
                                        <span class="praise-count"><my-icon icon="shoucang2" mode="right" />{{ v.praise_count }}</span>
                                        <span class="collect-count" v-if="$store.state.user"><my-icon icon="shoucang6" mode="right" />{{ v.collect_count }}</span>
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