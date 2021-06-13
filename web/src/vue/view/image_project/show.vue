<template>
    <div class="view">
        <div class="content">
            <!-- 详情 -->
            <div class="left image-subject" ref="image-subject">
                <div class="subject">
                    <div class="title run-action-title m-b-15">
                        <div class="left">{{ data.name }}</div>
                        <div class="right">

                            <my-button class="praise" @click="praiseHandle">
                                <my-loading size="16" v-if="val.pending.praiseHandle"></my-loading>
<!--                                <my-icon :class="{'run-red': data.is_praised }" icon="shoucang2" /> 喜欢 {{ data.praise_count }}-->
                                <my-icon :class="{'run-red': data.is_praised }" icon="shoucang2" /> 喜欢
                            </my-button>
<!--                            <my-button class="praise" @click="showFavorites"><my-icon icon="shoucang5" :class="{'run-red': data.is_collected}" /> 收藏 {{ data.collect_count }}</my-button>-->
                            <my-button class="praise" @click="showFavorites"><my-icon icon="shoucang5" :class="{'run-red': data.is_collected}" /> 收藏</my-button>
                        </div>
                    </div>

                    <div class="tags m-b-15">
                        <div class="left run-tags">
                            <span class="ico p-r-5"><my-icon icon="icontag" /></span>
                            <my-link class="tag" target="_blank" v-for="v in data.tags" :key="v.id" :href="`#/image_project/search?tag_id=${v.tag_id}`">{{ v.name }}</my-link>
                        </div>
                        <div class="right">
                            <span class="number">{{ data.images.length }}P</span>&nbsp;|&nbsp;<span class="praise"><my-icon icon="shoucang2" class="run-position-relative run-t--1" />{{ data.praise_count }}</span>&nbsp;|&nbsp;<span class="collect"><my-icon icon="shoucang5" class="run-position-relative run-t--1" />{{ data.collect_count }}</span>&nbsp;|&nbsp<span class="view-count"><my-icon icon="chakan" class="run-position-relative run-t--1" /> {{ data.view_count }}</span>&nbsp;|&nbsp;<span class="create-time"><my-icon icon="shijian" class="run-position-relative run-t--1" /> {{ data.created_at }}</span>
                        </div>
                    </div>

                    <div class="desc m-b-30">{{ data.description }}</div>
                    <div class="images" ref="images">
                        <img v-for="(v,k) in images.data" :src="v.src" @click="imageClick(k + 1)" class="image">
                    </div>
                </div>

                <div class="comment"></div>
                <!-- 相关推荐 -->
                <div class="recommend">
                    <div class="inner">
                        <div class="title">相关推荐</div>
                        <div class="list">

                            <a class="item" v-for="v in recommend.data" :key="v.id" :href="genUrl(`/image_project/${v.id}/show`)" @click.prevent="linkToImageProject(v)">
                                <div class="thumb">
                                    <div class="mask"><img :src="v.thumb ? v.thumb : TopContext.res.notFound" v-judge-img-size class="image judge-img-size"></div>
                                </div>
                                <div class="info">
                                    <div class="title">{{ v.name }}</div>
                                    <div class="time hide">{{ v.format_time }}</div>
                                </div>
                            </a>

                            <div class="empty" v-if="!val.pending.getRecommendData && recommend.data.length <= 0">暂无数据</div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- 其他 -->
            <div class="right misc" ref="misc">
                <div class="inner" :class="{fixed: val.fixed}">

                    <!-- 发布者 -->
                    <a class="user m-b-20" target="_blank" :href="genUrl(`/channel/${data.user.id}`)">
                        <div class="inner" :class="{fixed: val.fixed}">
                            <div class="avatar">
                                <div class="mask">
                                    <img :src="data.user ? data.user.avatar : TopContext.res.notFound" v-judge-img-size class="image judge-img-size" alt="">
                                </div>
                            </div>
                            <div class="name">{{ data.user ? data.user.username : '' }}</div>
                            <div class="data">
                                <a class="left" target="_blank" :href="genUrl(`/channel/${data.user_id}/my_focus_user`)">关注 {{ data.user.my_focus_user_count }}</a>
                                <a class="right" target="_blank" :href="genUrl(`/channel/${data.user_id}/focus_me_user`)">粉丝 {{ data.user.focus_me_user_count }}</a>
                            </div>
                            <div class="desc">{{ data.user.description }}</div>
                            <div class="action">
                                <my-button class="focus" @click.prevent="focusHandle">

                                    <template v-if="!data.user.focused"><my-icon icon="add" v-if="!data.user.focused" class="run-position-relative run-t--2" /> 关注</template>
                                    <template v-else>取消关注</template>
                                    <my-loading size="16" v-if="val.pending.focusHandle"></my-loading>
                                </my-button>
                                <my-button class="message" v-if="false" @click.prevent>私信</my-button>
                            </div>
                        </div>
                    </a>

                    <!-- 关联主题 -->
                    <a class="subject m-b-20" v-if="data.type === 'pro'" target="_blank" :href="genUrl(`/image_project/search?image_subject_id=${data.image_subject_id}`)">
                        <div class="info">
                            <div class="thumb">
                                <div class="mask">
                                    <img :src="data.image_subject ? data.image_subject.thumb : TopContext.res.notFound" v-judge-img-size class="image judge-img-size" alt="">
                                </div>
                            </div>
                            <div class="name">{{ data.image_subject.name }}</div>
                            <div class="desc">{{ data.image_subject.description }}</div>
                        </div>
                        <div class="attr" v-if="data.image_subject">
                            <!--                        <div class="title">简介</div>-->
                            <div class="list">
                                <div class="line" v-for="v in data.image_subject.__attr__">
                                    <span class="field">{{ v.field }}：</span>
                                    <span class="value">{{ v.value }}</span>
                                </div>
                            </div>
                        </div>
                        <!--                    <div class="action">-->
                        <!--                        <button class="focus"><my-icon icon="add" /> 关注</button>-->
                        <!--                    </div>-->

                    </a>
                    <!-- 最新发布 -->
                    <div class="newest" ref="newest">
                        <div class="inner" :class="{fixed: val.fixed}">
                            <div class="title">最新发布</div>
                            <div class="list">

                                <a class="item" v-for="v in newest.data" :Key="v.id" :href="genUrl(`/image_project/${v.id}/show`)" @click.prevent="linkToImageProject(v)">
                                    <div class="thumb">
                                        <div class="mask"><img :src="v.thumb ? v.thumb : TopContext.res.notFound" v-judge-img-size class="image judge-img-size"></div>
                                    </div>
                                    <div class="info">
                                        <div class="title">{{ v.name }}</div>
                                        <div class="time">{{ v.format_time }}</div>
                                    </div>
                                </a>

                                <div class="empty" v-if="!val.pending.getNewestData && newest.data.length <= 0">暂无数据</div>

                            </div>
                        </div>
                    </div>

                    <!-- app 下载 -->
                    <div class="mobile"></div>
                </div>
            </div>
        </div>

        <div class="loading" v-if="val.pending.getData">
            <my-loading width="50" height="50"></my-loading>
        </div>

        <!-- 图片预览 -->
        <div class="pic-preview-async-container" ref="pic-preview-async-container">
            <div class="pic-preview-async">
                <div class="preview">

                    <div class="header">
                        <div class="info"></div>
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
<!--                            <div class="item" v-for="v in data.images"><img :src="v.src" class="image" alt=""></div>-->
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

        <!-- 收藏夹 -->
        <div class="my-favorites hide" ref="my-favorites" @click="hideFavorites">
            <div class="inner" @click.stop>
                <div class="title">
                    <span class="text">收藏夹</span>
                    <my-button class="action close" @click="hideFavorites"><my-icon icon="close" size="12" /></my-button>
                </div>

                <div class="create">
                    <div class="title m-b-15 run-weight">创建收藏夹</div>
                    <div class="content">
                        收藏夹名称 <input type="text" class="input" placeholder="请输入收藏夹名称" v-model="collectionGroup.name" @keyup.enter="createAndJoinCollectionGroup"> <my-button class="button" @click="createAndJoinCollectionGroup"><my-loading size="16" v-if="val.pending.createAndJoinCollectionGroup" />&nbsp;创建并添加</my-button>
                    </div>
                </div>

                <div class="favorites">
                    <div class="title m-b-15 run-weight">收藏夹列表</div>
                    <div class="list">

                        <div class="item" v-for="v in favorites">
                            <div class="name">
                                {{ v.name }}
                                <span class="exists" v-if="v.is_inside">/已在此列表</span>
                                <my-button class="button" @click="collectionHandle(v)">
                                    <my-loading v-if="val.pending['collectionHandle_' + v.id]" size="16"></my-loading>
                                    <template v-if="v.is_inside">移除</template>
                                    <template v-else>添加</template>
                                </my-button>
                            </div>
                            <div class="info">
                                <span class="number">{{ v.count }}</span>
                            </div>
                        </div>

                        <div class="loading" v-if="val.pending.getFavorites">
                            <my-loading></my-loading>
                        </div>

                        <div class="empty" v-if="!val.pending.getFavorites && favorites.length <= 0">暂无数据</div>


                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script src="./js/show.js"></script>
<style scoped src="../public/css/base.css"></style>
<style scoped src="./css/show.css"></style>
