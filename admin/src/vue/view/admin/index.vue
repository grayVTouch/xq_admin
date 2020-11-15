<template>
<!--    <my-base ref="base">-->
        <div class="mask">

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
                            <div class="field">用户名：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.username"></div>
                        </div>

                        <div class="option">
                            <div class="field">性别：</div>
                            <div class="value">
                                <RadioGroup v-model="search.sex">
                                    <Radio v-for="(v,k) in $store.state.business.user.sex" :key="k" :label="k">{{ v }}</Radio>
                                </RadioGroup>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">手机：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.phone"></div>
                        </div>

                        <div class="option">
                            <div class="field">电子邮箱：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.email"></div>
                        </div>

                        <div class="option">
                            <div class="field">角色：</div>
                            <div class="value">
                                <my-select :data="roles" v-model="search.role_id" empty=""></my-select>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">超级管理员：</div>
                            <div class="value">
                                <RadioGroup v-model="search.is_root">
                                    <Radio v-for="(v,k) in $store.state.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                </RadioGroup>
                            </div>
                        </div>


                        <div class="option">
                            <div class="field"></div>
                            <div class="value">
                                <button type="submit" v-show="false"></button>
<!--                                <my-table-button size="medium" :loading="val.pending.getData" @click="searchEvent"><my-icon icon="search" mode="right" />搜索</my-table-button>-->
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
                        <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
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

                    <Table
                            ref="table"
                            border
                            :height="$store.state.context.table.height"
                            :columns="table.field"
                            :data="table.data"
                            :loading="val.pending.getData"
                            @on-selection-change="selectionChangeEvent"
                            @on-row-click="rowClickEvent"
                            @on-row-dblclick="rowDblclickEvent"
                            @on-sort-change="sortChangeEvent"
                    >
                        <template v-slot:role_id="{row,index}">{{ row.role ? `${row.role.name}【${row.role.id}】` : `unknow【${row.role_id}】` }}</template>
                        <template v-slot:avatar="{row,index}">
                            <img :src="row.avatar ? row.avatar : $store.state.context.res.notFound" @click="link(row.avatar , '__blank')" :height="$store.state.context.table.imageH" class="image">
                        </template>
                        <template v-slot:is_root="{row,index}">
                            <b :class="{green: row.is_root === 1 , red: row.is_root === 0}">{{ row.__is_root__ }}</b>
                        </template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button type="error" :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </Table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
                <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
            </div>

            <div class="line page">
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form ref="form" :id="current.id" :mode="val.mode" @on-success="getData"></my-form>
        </div>


<!--    </my-base>-->
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
