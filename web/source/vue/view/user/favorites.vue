<template>
    <user-base>
        <template slot="title">
            我的收藏 <my-loading size="16" v-if="val.pending.getCollectionGroup"></my-loading>
        </template>
        <template slot="action">
            <button v-ripple class="my-button small hide">批量操作</button>
        </template>
        <template slot="content">
            <div class="content-mask">
                <div class="favorites">
                    <div class="collection-groups">

                        <div class="create-favroites">
                            <button v-ripple @click="showCreateFavoritesForm" class="button"><my-icon icon="add" mode="right" />新建收藏夹</button>
                            <div class="create-form hide" ref="create-form">
                                <div class="mask" @click="hideCreateFavoritesForm"></div>
                                <form class="form" @submit.prevent="createCollectionGroup">
                                    <div class="line-for-input m-b-10">
                                        <input type="text" class="input" placeholder="请输入收藏夹名称" v-model="collectionGroupForm.name">
                                    </div>
                                    <div class="action">
                                        <button type="submit" class="my-button">
                                            <my-loading size="16" v-if="val.pending.createCollectionGroup"></my-loading> 创建
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="collection-group">

                            <div class="item" v-for="(v,k) in favorites" :class="{cur: v.id === currentCollectionGroup.id}" @click="switchCollectionGroup(v)" @mouseleave="hideCollectionGroupAction(v)">
                                <div class="action" v-ripple>
                                    <div class="name">
                                        <my-icon icon="shoucang5" mode="right" />{{ v.name }}
                                        <my-loading size="16" v-if="val.pending['destroyCollectionGroup_' + v.id]"></my-loading>
                                    </div>
                                    <div class="number" @mouseenter="showCollectionGroupAction(v)">
                                        <span class="text">{{ v.count }}</span>
                                        <span class="ico"><my-icon icon="youcecaidan"></my-icon></span>
                                    </div>
                                </div>
                                <div class="actions hide" :ref="`collection_group_actions_${v.id}`" @click.stop>
                                    <button class="edit" v-ripple @click="showUpdateFavoritesForm(v)">编辑收藏夹</button>
                                    <button class="delete" v-ripple @click="destroyCollectionGroupEvent(v)"><my-loading size="16" v-if="val.pending['destroyCollectionGroup_' + v.id]"></my-loading> 删除收藏夹</button>
                                </div>

                                <div class="update-form hide" :ref="`update-form-${v.id}`" @click.stop>
                                    <div class="mask" @click="hideUpdateFavoritesForm(v)"></div>
                                    <form class="form" @submit.prevent="updateCollectionGroup">
                                        <div class="line-for-input m-b-10">
                                            <input type="text" class="input" placeholder="请输入收藏夹名称" v-model="updateCollectionGroupForm.name">
                                        </div>
                                        <div class="action">
                                            <button type="submit" class="my-button">
                                                <my-loading size="16" v-if="val.pending['updateCollectionGroup_' + v.id]"></my-loading> 更新
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="collections">
                        <div class="list m-b-15">

                            <a class="item" target="_blank" v-for="v in collections.data" :key="v.id" v-if="v.relation_type === 'image_subject'" :href="`#/image_subject/${v.relation_id}/show`">

                                <div class="checkbox">
                                    <div class="mask">
                                        <input type="checkbox" class="input-checkbox">
                                    </div>
                                </div>

                                <div class="thumb">
                                    <div class="mask"><img :src="v.relation.thumb ? v.relation.__thumb__ : $store.state.context.res.notFound" alt="" class="image"></div>
                                </div>
                                <div class="info">
                                    <div class="title">
                                        <div class="name">{{ v.relation.name }}</div>
                                        <div class="action">
                                            <my-button class="button" @click.prevent="destroyCollection(v)">
                                                <my-loading size="16" v-if="val.pending['destroyCollection_' + v.id]"></my-loading>
                                                <my-icon icon="delete" class="v-a-t" mode="right"></my-icon>删除
                                            </my-button>
                                        </div>
                                    </div>
                                    <div class="info">{{ v.relation.create_time }} · {{ v.relation.view_count }}次观看 · {{ v.relation.collect_count }}次收藏 · {{ v.relation.praise_count }}次点赞 · {{ v.relation.user.username }}</div>
                                    <div class="desc">{{ v.relation.description }}</div>
                                </div>
                            </a>

                            <div class="empty" v-if="!val.pending.getCollections && collections.data.length === 0">暂无数据</div>

                            <div class="loading" v-if="val.pending.getCollections">
                                <my-loading size="30"></my-loading>
                            </div>

                        </div>

                        <div class="pager">
                            <my-page :total="collections.total" :limit="collections.limit" :page="collections.page" @on-change="toPage"></my-page>
                        </div>
                    </div>
                </div>
                <div class="filter" ref="filter" :class="{fixed: val.fixed}">
                    <div class="inner">

                        <div class="search-input">
                            <div class="inner">
                                <my-icon icon="search" class="ico v-a-t"></my-icon>
                                <input type="text" class="input" v-model="search.value" @keyup.enter="getCollectionGroup" placeholder="搜索收藏夹">
                                <my-loading class="pending" size="16" v-if="val.pending.getCollectionGroup"></my-loading>
                            </div>
                        </div>

                        <div class="relation-type">
                            <label class="item" v-ripple  v-for="(v,k) in relationType" :key="k">
                                <span class="name">{{ v }}</span>
                                <input type="radio" name="relation_type" :value="k" @change="getCollectionGroup" v-model="search.relation_type">
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        <!-- 编辑收藏夹 -->
        <div class="collection-group-layer hide">
            <div class="title"></div>
            <div class="form">
                <form @submit.prevent>
                    <div class="input-mask"><input type="text" class="input"></div>
                    <div class="action">
                        <button class="my-button">编辑</button>
                    </div>
                </form>
            </div>
        </div>
    </user-base>

</template>

<script src="./js/favorites.js"></script>

<style scoped src="../public/css/base.css"></style>
<style scoped src="./css/favorites.css"></style>