<template>

    <my-base>
        <template slot="search">
            <my-search-form @submit="searchEvent">

                <my-search-form-item name="id">
                    <input type="text" class="form-text" v-model="search.id" />
                </my-search-form-item>

                <my-search-form-item name="名称">
                    <input type="text" class="form-text" v-model="search.name" />
                </my-search-form-item>

                <my-search-form-item name="用户ID">
                    <input type="text" class="form-text" v-model="search.user_id" />
                </my-search-form-item>

                <my-search-form-item name="模块">
                    <my-select :data="modules" v-model="search.module_id" empty="" @change="getCategories"></my-select>
                    <my-loading v-if="myValue.pending.getModules"></my-loading>
                </my-search-form-item>

                <my-search-form-item name="分类">
                    <my-deep-select :data="categories" v-model="search.category_id" :has="false" empty=""></my-deep-select>
                    <my-loading v-if="myValue.pending.getCategories"></my-loading>
                    <span class="msg">请选择模块后操作</span>
                </my-search-form-item>

                <my-search-form-item name="类型">
                    <i-radio-group v-model="search.type">
                        <i-radio v-for="(v,k) in TopContext.business.video.type" :key="k" :label="k">{{ v }}</i-radio>
                    </i-radio-group>
                </my-search-form-item>

                <my-search-form-item name="视频专题ID">
                    <input type="number" class="form-text" v-model="search.video_project_id">
                </my-search-form-item>

                <my-search-form-item name="状态">
                    <i-radio-group v-model="search.status">
                        <i-radio v-for="(v,k) in TopContext.business.video.status" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                    </i-radio-group>
                </my-search-form-item>

                <my-search-form-item :show-separator="false">
                    <my-table-button @click="searchEvent"><my-icon icon="search" mode="right" />搜索</my-table-button>
                    <my-table-button @click="resetEvent" class="m-l-10"><my-icon icon="reset" mode="right" />重置</my-table-button>
                </my-search-form-item>
            </my-search-form>
        </template>

        <template slot="action">
            <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
            <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
            <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
            <my-table-button @click="retryProcessVideosEvent" :loading="myValue.pending.retryProcessVideos"><my-icon icon="reset" />重新处理 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
        </template>

        <template slot="page">
            <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
        </template>

        <template slot="table">
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
                <template v-slot:thumb="{row,index}"><my-table-image-preview :src="row.thumb"></my-table-image-preview></template>
                <template v-slot:thumb_for_program="{row,index}"><my-table-image-preview :src="row.thumb_for_program"></my-table-image-preview></template>
                <template v-slot:simple_preview="{row,index}">
                    <my-table-video-preview :src="row.simple_preview"></my-table-video-preview>
                </template>
                <template v-slot:preview="{row,index}">
                    <i-poptip trigger="hover" placement="right" :transfer="true">
                        <i-button @click.stop="openWindow(row.src)">悬浮预览</i-button>
                        <div slot="content" class="my-table-image-preview">
                            <img :src="src ? src : TopContext.res.notFound" class="image" @click.stop="openWindow(src)" alt="" />
                        </div>
                    </i-poptip>
                </template>
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
        </template>

        <my-form ref="form" :id="current.id" :mode="myValue.mode" @on-success="getData"></my-form>
    </my-base>
</template>

<script src="./js/index.js"></script>
<style src="../public/css/base.css"></style>
<style scoped>

</style>
