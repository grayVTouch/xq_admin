<template>
    <my-base ref="base">
        <div class="mask">

            <div class="line search hide"></div>

            <div class="line data">

                <div class="run-title">
                    <div class="left">
                        数据列表&nbsp;&nbsp;&nbsp;

                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项</my-table-button>
                    </div>
                    <div class="right"></div>
                </div>

                <div class="table">

                    <Table border width="100%" height="600" :columns="table.field" :data="table.data" @on-selection-change="selectedEvent">
                        <template v-slot:cn="{row,index}">
                            <template v-if="row.floor > 1">{{ '|' + '_'.repeat(row.floor * 2) + row.cn }}</template>
                            <template v-else>{{ row.cn }}</template>
                        </template>
                        <template v-slot:s_ico="{row,index}"><img :src="row.s_ico ? row.__s_ico__ : $store.state.context.res.notFound" class="image" height="40" @click.stop="link(row.__s_ico__)"></template>
                        <template v-slot:b_ico="{row,index}"><img :src="row.b_ico ? row.__b_ico__ : $store.state.context.res.notFound" class="image" height="40" @click.stop="link(row.__b_ico__)"></template>
                        <template v-slot:is_menu="{row,index}"><my-switch v-model="row.is_menu" :loading="val.pending['is_menu_' + row.id]" :extra="{id: row.id , field: 'is_menu'}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:is_view="{row,index}"><my-switch v-model="row.is_view" :loading="val.pending['is_view_' + row.id]" :extra="{id: row.id , field: 'is_view'}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:enable="{row,index}"><my-switch v-model="row.enable" :loading="val.pending['enable_' + row.id]" :extra="{id: row.id , field: 'enable'}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button type="error" :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </Table>

                </div>


            </div>

            <div class="line operation">
                <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项</my-table-button>
            </div>

            <div class="line page hide"></div>

            <my-form :title="title" :form="form" :mode="val.mode" v-model="val.drawer" :permission="permission" @on-success="getData"></my-form>
        </div>
    </my-base>
</template>

<script src="./js/index.js"></script>

<style scoped>
    .mask {

    }

    .mask > .line {
        margin-bottom: 15px;
    }

    .mask > .line:nth-last-of-type(1) {
        margin-bottom: 0;
    }

    .mask > .data > .table {
        overflow: hidden;
        overflow-x: auto;
        width: 100%;
    }
</style>