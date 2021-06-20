<template>
    <user-base>
        <template slot="title">历史记录</template>
        <template slot="action"></template>
        <template slot="content">
            <div class="content-mask">
                <div class="history">
                    <div class="groups m-b-15">

                        <div class="group" v-for="group in history.data">
                            <div class="title">{{ group.name }}</div>
                            <div class="list">
                                <template v-for="v in group.data">

                                    <!-- 图片专题 -->
                                    <a v-if="v.relation_type === 'image_project'" class="item" target="_blank" :href="`#/image_project/${v.relation_id}/show`">
                                        <div class="thumb">
                                            <div class="mask"><img :src="v.relation.thumb ? v.relation.thumb : TopContext.res.notFound" v-judge-img-size class="image judge-img-size"></div>
                                        </div>
                                        <div class="info">
                                            <div class="title">
                                                <div class="name">{{ v.relation.name }}</div>
                                                <div class="action">
                                                    <my-button class="button" @click.prevent="destroyHistory(v)">
                                                        <my-loading size="16" v-if="val.pending['destroyHistory_' + v.id]"></my-loading>
                                                        <my-icon icon="delete" class="v-a-t" mode="right"></my-icon>删除
                                                    </my-button>
                                                </div>
                                            </div>
                                            <div class="info">{{ getUsername(v.relation.user.username , v.relation.user.nickname) }} · {{ v.relation.view_count }}次观看 · {{ v.relation.collect_count }}次收藏 · {{ v.relation.praise_count }}次点赞 {{ v.created_at }}</div>
                                            <div class="desc">{{ v.relation.description }}</div>
                                        </div>
                                    </a>

                                    <a v-if="v.relation_type === 'video_project'" class="item" target="_blank" :href="`#/video_project/${v.relation_id}/show`">
                                        <div class="thumb">
                                            <div class="mask"><img :src="
                                                 v.relation ?
                                                    (v.relation.user_play_record ?
                                                        (v.relation.user_play_record.video ?
                                                            v.relation.user_play_record.video.__thumb__ :
                                                            TopContext.res.notFound
                                                        ) :
                                                        TopContext.res.notFound
                                                    ) :
                                                    TopContext.res.notFound"
                                                 v-judge-img-size class="image judge-img-size"></div>
                                        </div>
                                        <div class="info">
                                            <div class="title">
                                                <div class="name">{{ v.relation ? v.relation.name : '' }}</div>
                                                <div class="action">
                                                    <my-button class="button" @click.prevent="destroyHistory(v)">
                                                        <my-loading size="16" v-if="val.pending['destroyHistory_' + v.id]"></my-loading>
                                                        <my-icon icon="delete" class="v-a-t" mode="right"></my-icon>删除
                                                    </my-button>
                                                </div>
                                            </div>
                                            <div class="sub-name f-12 run-eee">{{ v.relation ?
                                                (v.relation.user_play_record ?
                                                (v.relation.user_play_record.video ?
                                                v.relation.user_play_record.video.name :
                                                ''
                                                ) :
                                                ''
                                                ) :
                                                '' }}</div>
                                            <div class="info">{{ getUsername(v.relation.user.username , v.relation.user.nickname) }} · {{ v.relation.view_count }}次观看 · {{ v.relation.collect_count }}次收藏 · {{ v.relation.praise_count }}次点赞 {{ v.created_at }}</div>
                                            <div class="desc">{{ v.relation.description }}</div>
                                        </div>
                                    </a>

                                </template>
                            </div>
                        </div>

                        <div class="empty" v-if="!val.pending.getHistory && history.data.length === 0">暂无数据</div>

                        <div class="loading" v-if="val.pending.getHistory">
                            <my-loading size="30"></my-loading>
                        </div>

                    </div>
                    <div class="pager">
                        <my-page :total="history.total" :limit="history.limit" :page="history.page" @on-change="toPage"></my-page>
                    </div>
                </div>
                <div class="filter" ref="filter" :class="{fixed: val.fixed}">
                    <div class="inner">

                        <div class="search-input">
                            <div class="inner">
                                <my-icon icon="search" class="ico v-a-t"></my-icon>
                                <input type="text" class="input" v-model="search.value" @keyup.enter="searchHistory" placeholder="搜索历史记录">
                            </div>
                        </div>

                        <div class="relation-type">
                            <label class="item" v-ripple  v-for="(v,k) in TopContext.business.relationType" :key="k">
                                <span class="name">{{ v }}</span>
                                <input type="radio" name="relation_type" :value="k" @change="searchHistory" v-model="search.relation_type">
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </template>
    </user-base>
</template>

<script src="./js/history.js"></script>

<style scoped src="./css/history.css"></style>
