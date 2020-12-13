<template>
    <div class="view-item">

        <div class="line search hide"></div>

        <div class="line">
            <div class="run-action-title">
                <div class="left">
                    <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                    <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
                    <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
                </div>
                <div class="right"></div>
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
                    <template v-slot:name="{row,index}">
                        <template v-if="row.floor > 1">{{ '|' + '_'.repeat((row.floor - 1) * 10) + row.name }}</template>
                        <template v-else>{{ row.name }}</template>
                    </template>
                    <template v-slot:user_id="{row,index}">{{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}</template>
                    <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                    <template v-slot:is_enabled="{row,index}"><my-switch v-model="row.is_enabled" :loading="val.pending['is_enabled_' + row.id]" :extra="{id: row.id , field: 'is_enabled'}" @on-change="updateBoolValEvent" /></template>
                    <template v-slot:action="{row , index}">
                        <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                        <my-table-button @click="editEvent(row)"><my-icon icon="add" />添加下级</my-table-button>
                        <my-table-button type="error" :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                    </template>
                </i-table>

            </div>

        </div>

        <div class="line operation">
            <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
            <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
            <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
        </div>

        <div class="line page hide"></div>

        <my-form ref="form" :id="current.id" :mode="val.mode" @on-success="getData"></my-form>
    </div>
</template>

<script src="./js/index.js"></script>
<style src="../public/css/base.css"></style>
<style scoped>

</style>
