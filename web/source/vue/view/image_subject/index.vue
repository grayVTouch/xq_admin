<template>
    <div class="view">
        <!-- 焦点栏 -->
        <div class="focus-bar">
            <div class="bg-color"></div>
            <div class="bg-image"></div>

            <div class="content">
                <div class="big-image">
                    <div class="mask"><img :src="imageSubject.length > 0 ? imageSubject[0].__path__ : './res/big.jpg'" alt="" class="image"></div>
                </div>
                <div class="small-image">
                    <div class="mask"><img :src="imageSubject.length > 1 ? imageSubject[1].__path__ : './res/01.jpg'" alt="" class="image"></div>
                    <div class="mask"><img :src="imageSubject.length > 2 ? imageSubject[2].__path__ : './res/02.jpg'" alt="" class="image"></div>
                    <div class="mask"><img :src="imageSubject.length > 3 ? imageSubject[3].__path__ : './res/03.jpg'" alt="" class="image"></div>
                    <div class="mask"><img :src="imageSubject.length > 4 ? imageSubject[4].__path__ : './res/04.jpg'" alt="" class="image"></div>

<!--                    <div class="mask"><img src="./res/01.jpg" alt="" class="image"></div>-->
<!--                    <div class="mask"><img src="./res/02.jpg" alt="" class="image"></div>-->
<!--                    <div class="mask"><img src="./res/03.jpg" alt="" class="image"></div>-->
<!--                    <div class="mask"><img src="./res/04.jpg" alt="" class="image"></div>-->
                </div>
            </div>
        </div>

        <!-- 内容 -->
        <div class="content">
            <!-- 标签列表 -->
            <div class="run-tags horizontal" ref="tags-selector-in-docs">
                <a class="tag" :class="{cur: curTag === 'newest' && search.tags.length < 1}" @click="newestInImageSubject">最新</a>
                <a class="tag" :class="{cur: curTag === 'hot' && search.tags.length < 1}" @click="hotInImageSubject">热门</a>
                <a class="tag" v-for="v in partHotTags" :class="{cur: curTag === 'tag_' + v.tag_id && search.tags.length < 1}" @click="getWithPagerByTagIdInImageSubject(v.tag_id)">{{ v.name }}</a>
                <a class="tag more" :class="{cur: search.tags.length > 0}" @click="showTagSelector">
                    更多标签
                    <span class="number" v-if="search.tags.length > 0">
                        <template v-if="search.tags.length < 10">{{ search.tags.length }}</template>
                        <template v-else>9+</template>
                    </span>
                </a>
            </div>

            <!-- 列表 -->
            <div class="list">

                <!-- 切换标签时的加载层 -->
                <div class="loading" v-if="val.pending.switchImages">
                    <my-loading width="50" height="50"></my-loading>
                </div>

                <div class="inner">
                    <div class="item card-box" v-for="v in images.data">
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
                            <div class="title" @click.stop="link(`#/image_subject/${v.id}/show`)">{{ v.name }}</div>
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

            <div class="loading">
                <my-loading v-if="!val.pending.switchImages && val.pending.images"></my-loading>
                <span class="end" v-if="images.end">到底了</span>
            </div>
        </div>

        <!-- 标签列表 -->
        <div class="run-tags vertical" ref="tags-selector-in-slidebar">
            <a class="tag" :class="{cur: curTag === 'newest' && search.tags.length < 1}" @click="newestInImageSubject">最新</a>
            <a class="tag" :class="{cur: curTag === 'hot' && search.tags.length < 1}" @click="hotInImageSubject">热门</a>
            <a class="tag" v-for="v in partHotTags" :class="{cur: curTag === 'tag_' + v.tag_id && search.tags.length < 1}" @click="getWithPagerByTagIdInImageSubject(v.tag_id)">{{ v.name }}</a>
            <a class="tag more" :class="{cur: search.tags.length > 0}" @click="showTagSelector">
                更多标签
                <span class="number" v-if="search.tags.length > 0">
                        <template v-if="search.tags.length < 10">{{ search.tags.length }}</template>
                        <template v-else>9+</template>
                    </span>
            </a>
        </div>

        <!-- 标签选择器 -->
        <div class="tag-selector hide" ref="tag-selector" @click="closeTagSelector">

            <div class="inner" @click.stop>
                <div class="title">
                    <div class="close" @click.stop="closeTagSelector">
                        <button class="close-btn"><i class="run-iconfont run-iconfont-guanbi"></i></button>
                    </div>
                    <div class="text">标签列表</div>
                    <div class="operation" @click="resetTagFilter">重置</div>
                </div>
                <div class="content">
                    <!-- 当前选中的标签 -->
                    <div class="line" v-if="search.tags.length > 0">
                        <div class="title m-b-15 f-14 weight">当前选择标签</div>
                        <div class="run-tags horizontal">
                            <span class="tag" v-for="v in search.tags" @click="unselectedTagByTagId(v.tag_id)">{{ v.name }}</span>
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
                        <div class="list run-tags horizontal" :class="{loading: val.pending.hotTagsWithPager}">
                            <my-loading v-if="val.pending.hotTagsWithPager"></my-loading>
                            <span class="tag" v-for="v in allHotTags.data" :class="{selected: search.tagIds.indexOf(v.tag_id) >= 0}" :key="v.id" @click="filterByTag(v)">{{ v.name }}</span>
                        </div>
                        <div class="pager">
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