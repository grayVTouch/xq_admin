<template>
    <div class="view">
        <!-- 焦点栏 -->
        <div class="focus-bar">
            <div class="bg-color"></div>
            <div class="bg-image"></div>
            <div class="bg-mask"></div>

            <div class="content">
                <div class="big-image">
                    <a class="mask" :href="imageSubject.length > 0 ? imageSubject[0].link : 'javascript:;'"><img :src="imageSubject.length > 0 ? imageSubject[0].__path__ : TopContext.res.notFound" v-judge-img-size alt="" class="image judge-img-size"></a>
                </div>
                <div class="small-image">
                    <a class="mask" :href="imageSubject.length > 1 ? imageSubject[1].link : 'javascript:;'"><img :src="imageSubject.length > 1 ? imageSubject[1].__path__ : TopContext.res.notFound" v-judge-img-size alt="" class="image judge-img-size"></a>
                    <a class="mask" :href="imageSubject.length > 2 ? imageSubject[2].link : 'javascript:;'"><img :src="imageSubject.length > 2 ? imageSubject[2].__path__ : TopContext.res.notFound" v-judge-img-size alt="" class="image judge-img-size"></a>
                    <a class="mask" :href="imageSubject.length > 3 ? imageSubject[3].link : 'javascript:;'"><img :src="imageSubject.length > 3 ? imageSubject[3].__path__ : TopContext.res.notFound" v-judge-img-size alt="" class="image judge-img-size"></a>
                    <a class="mask" :href="imageSubject.length > 4 ? imageSubject[4].link : 'javascript:;'"><img :src="imageSubject.length > 4 ? imageSubject[4].__path__ : TopContext.res.notFound" v-judge-img-size alt="" class="image judge-img-size"></a>
                </div>
            </div>
        </div>

        <!-- 内容 -->
        <div class="content">
            <!-- 标签列表 -->
            <div class="run-tags horizontal" ref="tags-selector-in-docs">
                <my-button class="tag" :class="{cur: curTag === 'newest' && search.tags.length < 1}" @click="newestInImageProjects">最新</my-button>
                <my-button class="tag" :class="{cur: curTag === 'hot' && search.tags.length < 1}" @click="hotInImageProjects">热门</my-button>
                <my-button class="tag" v-for="v in partHotTags.data" :key="v.id" :class="{cur: curTag === 'tag_' + v.tag_id && search.tags.length < 1}" @click="getWithPagerByTagIdInImageProject(v.tag_id)">{{ v.name }}</my-button>
                <my-button class="tag more" :class="{cur: search.tags.length > 0}" @click="showTagSelector">
                    更多标签
                    <span class="number" v-if="search.tags.length > 0">
                        <template v-if="search.tags.length < 10">{{ search.tags.length }}</template>
                        <template v-else>9+</template>
                    </span>
                </my-button>
            </div>

            <!-- 列表 -->
            <div class="list">

                <!-- 切换标签时的加载层 -->
                <div class="loading" v-if="val.pending.switchImages">
                    <my-loading width="50" height="50"></my-loading>
                </div>

                <div class="empty" v-if="!val.pending.switchImages && images.data.length <= 0"><my-icon icon="empty" size="40"></my-icon></div>

                <div class="inner">
                    <div class="item card-box" v-for="v in images.data" :key="v.id">
                        <!-- 封面 -->
                        <div class="thumb">
                            <a class="link" :href="genUrl(`/image_project/${v.id}/show`)" target="_blank">
                                <img :src="v.__thumb__" class="image judge-img-size" v-judge-img-size>
                                <div class="mask">
                                    <div class="top">
                                        <div class="type" v-if="v.type === 'pro'"><my-icon icon="zhuanyerenzheng" size="35" /></div>

                                        <div class="praise" v-ripple @click.prevent="praiseHandle(v)">
                                            <my-loading size="16" v-if="val.pending.praiseHandle"></my-loading>
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

                                <a class="tag" v-for="tag in v.tags" :key="tag.id" :href="`#/image_project/search?tag_id=${tag.tag_id}`">{{ tag.name }}</a>
                            </div>
                            <!-- 标题 -->
                            <div class="title"><a class="link" target="_blank" :href="`#/image_project/${v.id}/show`">{{ v.name }}</a></div>
                            <!-- 发布者 -->
                            <div class="user">
                                <div class="sender">
                                    <span class="avatar-outer"><img :src="v.user ? v.user.avatar : TopContext.res.avatar" alt="" class="image avatar"></span>
                                    <a class="name">{{ v.user ? v.user.nickname : 'unknow' }}</a>
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

            <div class="loading" v-if="images.total > 0">
                <my-loading v-if="!val.pending.switchImages && val.pending.images"></my-loading>
                <span class="end" v-if="images.data.length === images.total">到底了</span>
            </div>
        </div>

        <!-- 标签列表 -->
        <div class="run-tags vertical" ref="tags-selector-in-slidebar">
            <my-button class="tag" :class="{cur: curTag === 'newest' && search.tags.length < 1}" @click="newestInImageProjects">最新</my-button>
            <my-button class="tag" :class="{cur: curTag === 'hot' && search.tags.length < 1}" @click="hotInImageProjects">热门</my-button>
            <my-button class="tag" v-for="v in partHotTags.data" :key="v.id" :class="{cur: curTag === 'tag_' + v.tag_id && search.tags.length < 1}" @click="getWithPagerByTagIdInImageProject(v.tag_id)">{{ v.name }}</my-button>
            <my-button class="tag more" :class="{cur: search.tags.length > 0}" @click="showTagSelector">
                更多标签
                <span class="number" v-if="search.tags.length > 0">
                        <template v-if="search.tags.length < 10">{{ search.tags.length }}</template>
                        <template v-else>9+</template>
                    </span>
            </my-button>
        </div>

        <!-- 标签选择器 -->
        <div class="tag-selector hide" ref="tag-selector" @click="closeTagSelector">

            <div class="inner" @click.stop>
                <div class="title">
                    <div class="close" @click.stop="closeTagSelector">
                        <button class="close-btn"><i class="run-iconfont run-iconfont-guanbi"></i></button>
                    </div>
                    <div class="text">标签列表</div>
                    <div class="operation" v-ripple @click="resetTagFilter">重置</div>
                </div>
                <div class="content">
                    <!-- 当前选中的标签 -->
                    <div class="line" v-if="search.tags.length > 0">
                        <div class="title m-b-15 f-14 weight">当前选择标签</div>
                        <div class="run-tags horizontal">
                            <span class="tag" v-for="v in search.tags" :key="v.id" @click="unselectedTagByTagId(v.tag_id)">{{ v.name }}</span>
                        </div>
                    </div>
                    <div class="line mode-swith">
                        <div class="left">
                            <p class="title m-b-15 f-14 weight">宽松匹配</p>
                            <p class="desc f-12">
                                <template v-if="search.mode === 'strict'">严格匹配所有选中标签才认为满足要求</template>
                                <template v-if="search.mode === 'loose'">只要匹配中其中单个标签即认为满足要求</template>
                            </p>
                        </div>
                        <div class="right">
                            <my-switch v-model="search.mode" trueValue="loose" falseValue="strict" @on-change="filterModeChangeEvent"></my-switch>
                        </div>
                    </div>
                    <div class="line tags">
                        <div class="title f-14 weight">请选择过滤标签</div>
                        <div class="search">
                            <div class="inner">
                                <div class="ico"><my-icon icon="search" /></div>
                                <div class="input"><input type="text" class="form-text" v-model="allHotTags.value" @keyup.enter="hotTagsWithPager" placeholder="请搜索标签"></div>
                            </div>
                        </div>
                        <div class="list run-tags horizontal" :class="{loading: val.pending.hotTagsWithPager}">
                            <div class="mask" v-if="val.pending.hotTagsWithPager"><my-loading></my-loading></div>
                            <div class="empty" v-if="!val.pending.hotTagsWithPager && allHotTags.total <= 0">暂无相关记录</div>
                            <span class="tag" v-ripple v-for="v in allHotTags.data" :class="{selected: search.tagIds.indexOf(v.tag_id) >= 0}" :key="v.id" @click="filterByTag(v)">{{ v.name }}</span>
                        </div>
                        <div class="pager" v-if="allHotTags.total > 0">
                            <my-page :total="allHotTags.total" :limit="allHotTags.limit" :page="allHotTags.page" @on-change="tagPageEvent"></my-page>
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
