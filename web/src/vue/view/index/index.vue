<template>
    <div class="view">

        <!-- 焦点栏目 -->
        <div class="focus-bar">

            <div class="background">
                <!-- 背景轮播图 -->
                <div class="bg-image" :style="'background-image: url(\'' + background.image + '\')'"></div>
                <!-- 背景轮播图遮罩层 -->
                <div class="mask"></div>
            </div>

            <div class="content">
                <div class="slidebar" ref="slidebar">

                    <div class="pic-play-transform">
                        <div class="images">
                            <a class="link" v-for="v in homeSlideshow" :key="v.id" :href='v.link'><img :src="v.src" class="image" alt=""></a>
                        </div>
                        <div class="index"></div>
                        <div class="action prev"><i class="run-iconfont run-iconfont-prev01"></i></div>
                        <div class="action next"><i class="run-iconfont run-iconfont-next01"></i></div>
                    </div>

                </div>

                <div class="box">
                    <div class="inner">
                        <a class="item" v-for="(v,k) in hotImages" :key="v.id" v-if="k < 6" target="_blank" :href="`#/image_project/${v.id}/show`">
                            <img :src="v.thumb ? v.thumb : TopContext.res.notFound" alt="" v-judge-img-size class="image judge-img-size">
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
            <div class="group group-for-imageProject">
                <!-- 导航 -->
                <div class="run-action-title">
                    <div class="left">图片专题</div>
                    <div class="right">
                        <div class="tags">
                            <my-button class="tag" :class="{cur: group.imageProject.curTag === 'newest'}" @click="newestInImageProject">最新</my-button>
                            <my-button class="tag" :class="{cur: group.imageProject.curTag === 'hot'}" @click="getHotImageProject">热门</my-button>
                            <my-button class="tag" v-for="v in group.imageProject.tag.data" :key="v.id" :class="{cur: group.imageProject.curTag === 'tag_' + v.tag_id}" @click="getImageByTagId(v.tag_id)">{{ v.name }}</my-button>
                            <my-link class="tag" :href="genUrl('/image_project')">更多</my-link>
                        </div>
                        <div class="operation">
                            <my-button class="prev" :class="{disabled: group.imageProject.action.translateX === group.imageProject.action.maxTranslateX}" @click="prevByGroup('imageProject')"><my-icon icon="prev01" /></my-button>
                            <my-button class="next" :class="{disabled: group.imageProject.action.translateX === group.imageProject.action.minTranslateX}" @click="nextByGroup('imageProject')"><my-icon icon="next01" /></my-button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-imageProject">
                    <div class="loading" v-if="val.pending.imageProject"><my-loading width="50" height="50"></my-loading></div>
                    <div class="empty" v-if="!val.pending.imageProject && imageProjects.data.length <= 0"><my-icon icon="empty" size="40"></my-icon></div>
                    <div class="inner" ref="inner-for-imageProject">
                        <div class="item card-box" v-for="v in imageProjects.data" :key="v.id">
                            <!-- 封面 -->
                            <div class="thumb">
                                <a class="link" target="_blank" :href="`#/image_project/${v.id}/show`">
                                    <img :src="v.thumb ? v.thumb : TopContext.res.notFound" v-judge-img-size class="image judge-img-size">
                                    <div class="mask">
                                        <div class="top">
                                            <div class="type" v-if="v.type === 'pro'"><my-icon icon="zhuanyerenzheng" size="35" /></div>
                                            <div class="praise" v-ripple @click.prevent="praiseImageProject(v)">
                                                <my-loading size="16" v-if="val.pending.praiseImageProject"></my-loading>
                                                <my-icon icon="shoucang2" :class="{'run-red': v.is_praised }" /> 喜欢
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

                                    <a class="tag" target="_blank" v-for="tag in v.tags" :href="`#/image_project/search?tag_id=${tag.tag_id}`">{{ tag.name }}</a>
                                </div>
                                <!-- 标题 -->
                                <div class="title"><a target="_blank" :href="`#/image_project/${v.id}/show`">{{ v.name }}</a></div>
                                <!-- 发布者 -->
                                <div class="user">
                                    <div class="sender">
                                        <span class="avatar-outer"><img :src="v.user.avatar ? v.user.avatar : TopContext.res.avatar" alt="" class="image avatar"></span>
                                        <a class="name">{{ v.user.nickname }}</a>
                                    </div>
                                    <div class="action"></div>
                                </div>
                                <!-- 统计信息 -->
                                <div class="info">
                                    <div class="left"><my-icon icon="shijian" class="ico" mode="right" /> {{ v.created_at }}</div>
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

            <!-- 图片 -->
            <div class="group group-for-image" v-if="true">
                <!-- 导航 -->
                <div class="run-action-title">
                    <div class="left">图片</div>
                    <div class="right">
                        <div class="tags">
                            <my-button class="tag" :class="{cur: group.videoProject.curTag === 'newest'}" @click="newestInVideoProject">最新</my-button>
                            <my-button class="tag" :class="{cur: group.videoProject.curTag === 'hot'}" @click="hotInVideoProject">热门</my-button>
                            <my-button class="tag" v-for="v in group.videoProject.tag.data" :key="v.id" :class="{cur: group.videoProject.curTag === 'tag_' + v.tag_id}" @click="getVideoProjectsByTagId(v.tag_id)">{{ v.name }}</my-button>
                            <my-link class="tag" :href="genUrl('/video_project/search')">更多</my-link>
                        </div>
                        <div class="operation">
                            <my-button class="prev" :class="{disabled: group.videoProject.action.translateX === group.videoProject.action.maxTranslateX}" @click="prevByGroup('videoProject')"><my-icon icon="prev01" /></my-button>
                            <my-button class="next" :class="{disabled: group.videoProject.action.translateX === group.videoProject.action.minTranslateX}" @click="nextByGroup('videoProject')"><my-icon icon="next01" /></my-button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-image">

                    <div class="item" v-for="v in listData">
                        <a class="link">
                            <div class="mask">
                                <img class="image" :src="v" v-judge-img-size alt="">
                            </div>
                            <div class="actions">
                                <div class="top">
                                    <div class="right praise" v-ripple @click.prevent="praiseImageProject(v)">
                                        <my-loading size="16" v-if="val.pending.praiseImageProject"></my-loading>
                                        <my-icon icon="shoucang2" :class="{'run-red': true }" /> 喜欢
                                    </div>
                                </div>
                                <div class="btm">
                                    <span class="view-count"><my-icon icon="chakan" mode="right" />11</span>
                                    <span class="praise-count"><my-icon icon="shoucang2" mode="right" />2</span>
                                    <span class="collect-count" v-if="$store.state.user"><my-icon icon="shoucang6" mode="right" />3</span>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

            <!-- 视频专题 -->
            <div class="group group-for-video-project">
                <!-- 导航 -->
                <div class="run-action-title">
                    <div class="left">视频专题</div>
                    <div class="right">
                        <div class="tags">
                            <my-button class="tag" :class="{cur: group.videoProject.curTag === 'newest'}" @click="newestInVideoProject">最新</my-button>
                            <my-button class="tag" :class="{cur: group.videoProject.curTag === 'hot'}" @click="hotInVideoProject">热门</my-button>
                            <my-button class="tag" v-for="v in group.videoProject.tag.data" :key="v.id" :class="{cur: group.videoProject.curTag === 'tag_' + v.tag_id}" @click="getVideoProjectsByTagId(v.tag_id)">{{ v.name }}</my-button>
                            <my-link class="tag" :href="genUrl('/video_project/search')">更多</my-link>
                        </div>
                        <div class="operation">
                            <my-button class="prev" :class="{disabled: group.videoProject.action.translateX === group.videoProject.action.maxTranslateX}" @click="prevByGroup('videoProject')"><my-icon icon="prev01" /></my-button>
                            <my-button class="next" :class="{disabled: group.videoProject.action.translateX === group.videoProject.action.minTranslateX}" @click="nextByGroup('videoProject')"><my-icon icon="next01" /></my-button>
                        </div>
                    </div>
                </div>

                <div class="list" ref="list-for-videoProject">
                    <div class="loading" v-if="val.pending.videoProject"><my-loading width="50" height="50"></my-loading></div>
                    <div class="empty" v-if="!val.pending.videoProject && videoProjects.data.length <= 0">
                        <my-icon icon="empty" size="40"></my-icon>
                    </div>
                    <div class="inner" ref="inner-for-videoProject">


                        <div class="item card-box" v-for="v in videoProjects.data" :key="v.id">
                            <!-- 封面 -->
                            <div class="thumb">
                                <a class="link" target="_blank" :href="genUrl(`/video_project/${v.id}/show`)">
                                    <img :src="v.thumb ? v.thumb : TopContext.res.notFound" v-judge-img-size class="image judge-img-size">
                                    <div class="mask">
                                        <div class="top">
                                            <div class="type"><my-icon icon="zhuanyerenzheng" size="35" /></div>
                                            <div class="praise" v-ripple @click.prevent="praiseVideoProject(v)">
                                                <my-loading size="16" v-if="val.pending.praiseVideoProject"></my-loading>
                                                <my-icon icon="shoucang2" :class="{'run-red': v.is_praised }" /> 喜欢
                                            </div>
                                        </div>
                                        <div class="btm">
                                            <div class="count">{{ v.min_index | zero_fill }}-{{ v.max_index | zero_fill }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="introduction">
                                <!-- 标签 -->
                                <div class="tags">
                                    <span class="ico"><my-icon icon="icontag" size="18" /></span>
                                    <a class="tag" target="_blank" v-for="tag in v.tags" :href="genUrl(`/video_project/search?tag_id=${tag.tag_id}`)">{{ tag.name }}</a>
                                </div>
                                <!-- 标题 -->
                                <div class="title"><a target="_blank" :href="genUrl(`/video_project/${v.id}/show`)">{{ v.name }}</a></div>
                                <!-- 统计信息 -->
                                <div class="info">
                                    <div class="left"><my-icon icon="shijian" class="ico" mode="right" /> {{ v.created_at }}</div>
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
