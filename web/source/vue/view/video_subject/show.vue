<template>
    <div class="view">

        <div class="left info">
            <div class="video-container" ref="video-container">
                <my-video ref="my-video"></my-video>
            </div>

            <div class="play-info">
                <div class="info">
                    <div class="name">{{ videoSubject.current.name }}</div>
                    <div class="statistics">
                        {{ videoSubject.current.view_count }}观看 · {{ videoSubject.current.play_count }}播放 · {{ videoSubject.current.create_time }}
                    </div>
                </div>
                <div class="actions">
                    <div class="action praise run-red" v-ripple><my-icon icon="shoucang2" size="24"></my-icon>{{ videoSubject.current.praise_count }}</div>
                    <div class="action hate"><my-icon icon="shoucang2" size="24"></my-icon>{{ videoSubject.current.against_count }}</div>
                    <div class="action collect"><my-icon icon="shoucang5" size="24"></my-icon>{{ videoSubject.current.collect_count }}</div>
                </div>
            </div>

            <div class="video-subject">
                <div class="thumb"><img :src="videoSubject.__thumb__ ? videoSubject.__thumb__ : $store.state.context.res.notFound" v-judge-img-size class="image judge-img-size"></div>
                <div class="info">

                    <div class="two-column-layout core">
                        <div class="title">
                            <div class="name">{{ videoSubject.name }}</div>
                            <div class="info">
                                <div class="statistics-1">{{ videoSubject.view_count }}观看 · {{ videoSubject.play_count }}播放 · {{ videoSubject.collect_count }}收藏 · {{ videoSubject.praise_count }}点赞· {{ videoSubject.against_count }}反对</div>
                                <div class="statistics-2">{{ videoSubject.__status__ }}, 全{{ videoSubject.count }}话</div>
                            </div>
                        </div>
                        <div class="item company" v-if="videoSubject.video_company">
                            <div class="field">视频制作公司</div>
                            <div class="value">{{ videoSubject.video_company.name }}</div>
                        </div>
                    </div>

                    <div class="two-column-layout time">
                        <div class="item release-time">
                            <div class="field">发布时间</div>
                            <div class="value">{{ videoSubject.release_time }}</div>
                        </div>
                        <div class="item end-time">
                            <div class="field">完结时间</div>
                            <div class="value">{{ videoSubject.end_time }}</div>
                        </div>
                        <div class="item upload-time">
                            <div class="field">上传时间</div>
                            <div class="value">{{ videoSubject.create_time }}</div>
                        </div>
                    </div>

                    <div class="desc">{{ videoSubject.description }}</div>
                    <div class="item run-tags">
                        <my-link class="tag border-tag" target="_blank" v-for="v in videoSubject.tags">{{ v.name }}</my-link>
                    </div>
                </div>
                <div class="mark">
                    <div class="flag hide">UNCENSORED</div>
                    <div class="score">{{ videoSubject.score }}</div>
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
                        <div class="item" v-ripple v-for="(v,k) in indexRange.group.index" :key="k" :class="{cur: indexRange.current.value === `${v.min}-${v.max}`}" @click="switchIndexRangeByMinAndMax(v.min , v.max)">{{ v.min + '-' + v.max }}</div>
<!--                        <div class="item cur">151-180</div>-->
                    </div>
                    <div class="right" >
<!--                        <div class="item more" v-if="videoSubject.videos.length > 120" @click="val.loadMoreIndex = true">加载更多<my-icon icon="arrow"></my-icon>-->
                        <div class="item more" :class="{cur: indexRange.current.more}" v-ripple @click.stop="showMoreIndex" v-if="videoSubject.max_index > indexRange.split * indexRange.indexGroupCount">
                            <template v-if="indexRange.current.more">{{ indexRange.current.min + '-' + indexRange.current.max }}</template>
                            <template v-else>加载更多</template>
                            <my-icon icon="arrow" :class="{spread: !val.loadMoreIndex}"></my-icon>
                        </div>
                    </div>
                    <div class="more-index" v-if="val.loadMoreIndex" @click.stop>
                        <div class="item" v-ripple v-for="(v,k) in indexRange.group.other" :key="k" :class="{cur: indexRange.current.value === `${v.min}-${v.max}`}" @click="switchIndexRangeByMinAndMax(v.min , v.max)">{{ v.min + '-' + v.max }}</div>
<!--                        <div class="item">151-180</div>-->
                    </div>
                </div>

                <!-- 索引 -->
                <div class="indexs" :class="{pending: val.pending.videosInRange , empty: !val.pending.videosInRange && indexRange.videos.length === 0}">

                    <div class="item" v-for="v in indexRange.videos" :class="{cur: v.id === videoSubject.current.id}" :key="v.id" @mouseenter="showVideo(v)" @mouseleave="hideVideo(v)" @click="ins.videoPlayer.index(v.index)">
                        <div class="thumb">
                            <div class="image-mask" v-show="v.show_type === 'image' || !v.video_loaded"><img :src="v.__thumb__" v-judge-img-size class="image judge-img-size" alt=""></div>
                            <div class="video-mask" :ref="'video-mask-' + v.id" v-show="v.show_type === 'video' && v.video_loaded">
                                <video :ref="'video-' + v.id" loop="loop" autoplay="autoplay" muted="muted"></video>
                            </div>
                            <div class="info"><template v-if="v.is_hd">HD</template> {{ v.__duration__ }}</div>
                            <div class="progress" v-if="!v.video_loaded && v.show_type === 'video'">
                                <div class="ratio" :style="'transform: scaleX(' + v.video_loaded_ratio + ')'"></div>
                            </div>
                        </div>
                        <div class="info">
                            <div class="name">test</div>
                            <div class="subject">Pink Pineapple</div>
                            <div class="statistics">1000次观看</div>
                        </div>
                    </div>

                    <div class="pending" v-if="val.pending.videosInRange"><my-loading></my-loading></div>
                    <div class="empty" v-if="!val.pending.videosInRange && indexRange.videos.length === 0"><my-icon icon="empty" size="40"></my-icon></div>
                </div>

            </div>

            <div class="video-series">
                <div class="title">系列视频</div>
                <div class="list">

                    <div class="video-subject" v-for="v in videoSubjectsInSeries" :key="v.id" @click="linkAndRefresh(`/video_subject/${v.id}/show`)">
                        <div class="thumb"><img :src="v.__thumb__ ? v.__thumb__ : $store.state.context.res.notFound" class="judge-img-size" v-judge-img-size alt=""></div>
                        <div class="info">
                            <div class="name">{{ v.name }}</div>
                            <div class="statistics">
                                <div class="index-count">全{{ v.count }}话</div>
                                <div class="statistics">{{ v.play_count }}播放</div>
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