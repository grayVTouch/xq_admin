<template>

    <my-base>
        <template slot="search">
            <my-search-form @submit="searchEvent">
                <my-search-form-item name="id">
                    <input type="text" class="form-text" v-model="search.id" />
                </my-search-form-item>

                <my-search-form-item name="name">
                    <input type="text" class="form-text" v-model="search.name" />
                </my-search-form-item>

                <my-search-form-item name="模块">
                    <my-select :data="modules" empty="" v-model="search.module_id"></my-select>
                    <my-loading v-if="myValue.pending.getModules"></my-loading>
                </my-search-form-item>

                <my-search-form-item name="视频系列ID">
                    <input type="text" class="form-text" v-model="search.video_series_id" />
                </my-search-form-item>

                <my-search-form-item name="视频制作公司ID">
                    <input type="text" class="form-text" v-model="search.video_company_id" />
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
                <template v-slot:name="{row,index}">{{ row.name }}</template>
                <template v-slot:thumb="{row,index}"><my-table-image-preview :src="row.thumb"></my-table-image-preview></template>
                <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                <template v-slot:category_id="{row,index}">{{ row.category ? `${row.category.name}【${row.category.id}】` : `unknow【${row.category_id}】` }}</template>
                <template v-slot:video_series_id="{row,index}">{{ row.video_series ? `${row.video_series.name}【${row.video_series.id}】` : `unknow【${row.video_series_id}】` }}</template>
                <template v-slot:video_company_id="{row,index}">{{ row.video_company ? `${row.video_company.name}【${row.video_company.id}】` : `unknow【${row.video_company_id}】` }}</template>
                <template v-slot:user_id="{row,index}">{{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}</template>
                <template v-slot:status="{row,index}"><b :class="{'run-red': row.status === -1 , 'run-gray': row.status === 0 , 'run-green': row.status === 1}">{{ row.__status__ }}</b></template>
                <template v-slot:tags="{row,index}">
                    <i-poptip placement="right" width="400" title="标签" :transfer="true" trigger="hover">
                        <i-button>悬浮可查看详情</i-button>
                        <div slot="content">
                            <table class="line-table">
                                <tbody>
                                <tr v-for="v in row.tags" :key="v.id">
                                    <td>{{ v.name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </i-poptip>
                </template>
                <template v-slot:status="{row,index}">
                    <span class="run-weight" :class="{'run-red': row.status == 'making' , 'run-green': row.status === 'completed' , 'run-gray': row.status === 'terminated'}">{{ row.__status__ }}</span>
                </template>
                <template v-slot:action="{row , index}">
                    <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                    <my-table-button type="error" :loading="myValue.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                </template>
            </i-table>
        </template>

        <my-form ref="form" :mode="myValue.mode" :id="current.id" @on-success="getData"></my-form>

    </my-base>
</template>

<script src="./js/index.js"></script>
<style scoped>

</style>
