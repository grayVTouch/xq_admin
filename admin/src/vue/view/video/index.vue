<template>
<!--    <my-base ref="base">-->
        <div class="view-item">

            <div class="line search">
                <div class="run-title">
                    <div class="left">筛选</div>
                    <div class="right"></div>
                </div>

                <div class="filter-option">
                    <form @submit.prevent="searchEvent">
                        <div class="option">
                            <div class="field">id：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.id"></div>
                        </div>

                        <div class="option">
                            <div class="field">名称：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.name"></div>
                        </div>

                        <div class="option">
                            <div class="field">用户id：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.user_id"></div>
                        </div>


                        <div class="option">
                            <div class="field">模块：</div>
                            <div class="value">
                                <my-select :data="modules" v-model="search.module_id" empty="" @change="getCategories"></my-select>
                                <my-loading v-if="myValue.pending.getModules"></my-loading>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">分类：</div>
                            <div class="value">
                                <my-deep-select :data="categories" v-model="search.category_id" :has="false" empty=""></my-deep-select>
                                <my-loading v-if="myValue.pending.getCategories"></my-loading>
                                <span class="msg">请选择模块后操作</span>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">类型：</div>
                            <div class="value">
                                <i-radio-group v-model="search.type">
                                    <i-radio v-for="(v,k) in TopContext.business.video.type" :key="k" :label="k">{{ v }}</i-radio>
                                </i-radio-group>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">视频专题：</div>
                            <div class="value">
                                <input type="number" class="form-text" v-model="search.video_project_id">
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">状态：</div>
                            <div class="value">
                                <i-radio-group v-model="search.status">
                                    <i-radio v-for="(v,k) in TopContext.business.video.status" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field"></div>
                            <div class="value">
                                <button type="submit" v-show="false"></button>
                                <my-table-button @click="searchEvent"><my-icon icon="search" mode="right" />搜索</my-table-button>
                                <my-table-button @click="resetEvent"><my-icon icon="reset" mode="right" />重置</my-table-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="line">
                <div class="run-action-title">
                    <div class="left">
                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                        <my-table-button type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
                        <my-table-button @click="retryProcessVideosEvent" :loading="myValue.pending.retryProcessVideos"><my-icon icon="reset" />重新处理 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
                    </div>
                    <div class="right">
                        <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
                    </div>
                </div>
            </div>

            <div class="line data">

                <div class="run-title">
                    <div class="left">数据列表</div>
                    <div class="right"></div>
                </div>

                <div class="table">

                    <i-table
                            ref="table"
                            class="w-r-100"
                            border
                            :height="TopContext.table.height"
                            :columns="table.field"
                            :data="table.data"
                            :loading="myValue.pending.getData"
                            @on-selection-change="selectionChangeEvent"
                            @on-row-click="rowClickEvent"
                            @on-row-dblclick="rowDblclickEvent"
                            @on-sort-change="sortChangeEvent"
                    >
                        <template v-slot:thumb="{row,index}"><my-table-preview :src="row.thumb"></my-table-preview></template>
                        <template v-slot:thumb_for_program="{row,index}"><my-table-preview :src="row.thumb_for_program"></my-table-preview></template>
                        <template v-slot:simple_preview="{row,index}">
                            <Tooltip content="点击播放" placement="right" :transfer="true">
                                <video :src="row.simple_preview" @click="restartPlayVideo" :height="TopContext.table.imageH"></video>
                            </Tooltip>
                        </template>
                        <template v-slot:preview="{row,index}"><my-table-preview :src="row.preview"></my-table-preview></template>
                        <template v-slot:user_id="{row,index}">
                            {{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}
                        </template>
                        <template v-slot:module_id="{row,index}">
                            {{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}
                        </template>
                        <template v-slot:category_id="{row,index}">
                            {{ row.category ? `${row.category.name}【${row.category.id}】` : `unknow【${row.category_id}】` }}
                        </template>
                        <template v-slot:video_project_id="{row,index}">
                            {{ row.type === 'pro' ? (row.video_project ? `${row.video_project.name}【${row.video_project.id}】` : `unknow【${row.video_project_id}】`) : null }}
                        </template>
                        <template v-slot:status="{row,index}">
                            <b :class="{'run-red': row.status === -1 , 'run-gray': row.status === 0 , 'run-green': row.status === 1}">{{ row.__status__ }}</b>
                        </template>
                        <template v-slot:video_process_status="{row,index}">
                            <b :class="{'run-gray': row.video_process_status === -1 , 'run-red': row.video_process_status === 0 , 'run-green': row.video_process_status >= 1}">{{ row.__video_process_status__ }}</b>
                        </template>
                        <template v-slot:file_process_status="{row,index}">
                            <b :class="{'run-gray': row.file_process_status === -1 , 'run-red': row.file_process_status === 0 , 'run-green': row.file_process_status >= 1}">{{ row.__file_process_status__ }}</b>
                        </template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button @click="retryProcessVideoEvent(row)" :loading="myValue.pending['retry_' + row.id]" v-if="row.process_status === -1"><my-icon icon="reset" />重新处理</my-table-button>
                            <my-table-button type="error" :loading="myValue.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </i-table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
                <my-table-button @click="retryProcessVideosEvent" :loading="myValue.pending.retryProcessVideos"><my-icon icon="reset" />重新处理 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
            </div>

            <div class="line page">
<!--                <Page :total="table.total" :page-size="TopContext.limit" :current="table.page" :show-total="true" :show-sizer="false" :show-elevator="true"  @on-change="pageEvent" />-->
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form ref="form" :id="current.id" :mode="myValue.mode" @on-success="getData"></my-form>

        </div>
</template>

<script src="./js/index.js"></script>
<style src="../public/css/base.css"></style>
<style scoped>

</style>
