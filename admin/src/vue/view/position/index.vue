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
                        <div class="value"><input type="text" class="form-text" v-model="search.id" /></div>
                    </div>

                    <div class="option">
                        <div class="field">名称：</div>
                        <div class="value"><input type="text" class="form-text" v-model="search.name" /></div>
                    </div>

                    <div class="option">
                        <div class="field">模块：</div>
                        <div class="value">
                            <my-select :data="modules" empty="" v-model="search.module_id"></my-select>
                            <my-loading v-if="val.pending.getModules"></my-loading>
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
                    <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
<!--                    <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>-->
<!--                    <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>-->

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
                        :loading="val.pending.getData"
                        @on-selection-change="selectionChangeEvent"
                        @on-row-click="rowClickEvent"
                        @on-row-dblclick="rowDblclickEvent"
                        @on-sort-change="sortChangeEvent"
                >
                    <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                </i-table>

            </div>

        </div>

        <div class="line operation">
            <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
<!--            <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>-->
<!--            <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>-->

        </div>

        <div class="line page">
            <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
        </div>

        <my-form ref="form" :id="current.id" :mode="val.mode" @on-success="getData"></my-form>
    </div>
</template>

<script src="./js/index.js"></script>

<style src="../public/css/base.css"></style>
<style scoped>
</style>
