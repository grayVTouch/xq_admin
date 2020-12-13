<template>
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
                        <div class="field">模块：</div>
                        <div class="value">
                            <my-select :data="modules" v-model="search.module_id" empty=""></my-select>
                            <my-loading v-if="myValue.pending.getModules"></my-loading>
                        </div>
                    </div>

                    <div class="option">
                        <div class="field"></div>
                        <div class="value">
                            <button type="submit" v-show="false"></button>
                            <my-table-button @click="searchEvent"><my-icon icon="search" mode="right" />搜索</my-table-button>
                            <my-table-button @click="resetEvent" class="m-l-10"><my-icon icon="reset" mode="right" />重置</my-table-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="line">
            <div class="run-action-title">
                <div class="left">
                    <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                    <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
                    <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
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
                        @on-selection-change="selectionChangeEvent"
                        :loading="myValue.pending.getData"
                        @on-row-click="rowClickEvent"
                        @on-row-dblclick="rowDblclickEvent"
                        @on-sort-change="sortChangeEvent"
                >
                    <template v-slot:name="{row,index}">{{ row.name + `【${row.module ? row.module.name : 'unknow'}】` }}</template>
<!--                        <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>-->
                    <template v-slot:thumb="{row,index}">
                        <i-poptip placement="right" :transfer="true" trigger="hover">
                            <img :src="row.thumb ? row.thumb : TopContext.res.notFound" class="image" :height="TopContext.table.imageH" @click.stop="openWindow(row.thumb)" alt="">
                            <div class="table-preview-image-style" slot="content">
                                <img :src="row.thumb ? row.thumb : TopContext.res.notFound" class="image" @click.stop="openWindow(row.thumb)" alt="">
                            </div>
                        </i-poptip>
                    </template>

                    <template v-slot:attr="{row,index}">
                        <i-poptip placement="right" width="400" title="关联主体属性" :transfer="true" trigger="hover">
                            <i-button>悬浮可查看详情</i-button>
                            <div slot="content">
                                <table class="line-table">
                                    <tbody>
                                        <tr v-for="(v,k) in row.__attr__" :key="k">
                                            <td>{{ v.field }}</td>
                                            <td>{{ v.value }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </i-poptip>
                    </template>
                    <template v-slot:user_id="{row,index}">{{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}</template>
                    <template v-slot:status="{row,index}"><b :class="{'run-red': row.status === -1 , 'run-gray': row.status === 0 , 'run-green': row.status === 1}">{{ row.__status__ }}</b></template>
                </i-table>

            </div>

        </div>

        <div class="line operation">
            <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
            <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
            <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>

        </div>

        <div class="line page">
            <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
        </div>

        <my-form ref="form" :id="current.id" :mode="myValue.mode" @on-success="getData"></my-form>
    </div>
</template>

<script src="./js/index.js"></script>
<style src="../public/css/base.css"></style>
<style scoped>
</style>
