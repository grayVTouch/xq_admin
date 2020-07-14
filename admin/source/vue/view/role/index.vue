<template>
    <my-base ref="base">
        <div class="mask">

            <div class="line search">
                <div class="run-title">
                    <div class="left">筛选</div>
                    <div class="right"></div>
                </div>

                <div class="filter-option">
                    <div class="option">
                        <div class="field">id：</div>
                        <div class="value"><input type="text" class="form-text" v-model="search.id"></div>
                    </div>

                    <div class="option">
                        <div class="field">名称：</div>
                        <div class="value"><input type="text" class="form-text" v-model="search.name"></div>
                    </div>

                    <div class="option">
                        <div class="field"></div>
                        <div class="value">
                            <Button v-ripple type="primary" :loading="val.pending.getData" @click="searchEvent"><my-icon icon="search" mode="right" />搜索</Button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="line order">
                <div class="run-title">
                    <div class="left">排序</div>
                    <div class="right"></div>
                </div>

                <div class="filter-option">

                    <div class="option">
                        <div class="field">id：</div>
                        <div class="value">
                            <RadioGroup v-model="search.order">
                                <Radio label="id|asc">升序</Radio>
                                <Radio label="id|desc">降序</Radio>
                            </RadioGroup>
                        </div>
                    </div>

                    <div class="option">
                        <div class="field">权重：</div>
                        <div class="value">
                            <RadioGroup v-model="search.order">
                                <Radio label="weight|asc">升序</Radio>
                                <Radio label="weight|desc">降序</Radio>
                            </RadioGroup>
                        </div>
                    </div>

                    <div class="option">
                        <div class="field"></div>
                        <div class="value">
                            <Button v-ripple type="primary" :loading="val.pending.getData" @click="searchEvent"><my-icon icon="search" mode="right" />搜索</Button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="line">
                <div class="run-action-title">
                    <div class="left">
                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项 （{{ val.selectedIds.length }}）</my-table-button>
                    </div>
                    <div class="right">
                        <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
                    </div>
                </div>
            </div>

            <div class="line data">

                <div class="run-title">
                    <div class="left">
                        数据列表&nbsp;&nbsp;&nbsp;

<!--                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>-->
<!--                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项 （{{ val.selectedIds.length }}）</my-table-button>-->
                    </div>
                    <div class="right">
<!--                        <Page :total="table.total" :page-size="$store.state.context.limit" :current="table.page" :show-total="true" :show-sizer="false" :show-elevator="true"  @on-change="pageEvent" />-->
                    </div>
                </div>

                <div class="table">

                    <Table border :height="$store.state.context.table.height" :columns="table.field" :data="table.data" @on-selection-change="selectedEvent">
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button @click="allocateEvent(row)"><my-icon icon="privilege" />权限分配</my-table-button>
                            <my-table-button type="error" :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </Table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项 （{{ val.selectedIds.length }}）</my-table-button>
            </div>

            <div class="line page">
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form-modal v-model="val.modal" :title="title" :loading="val.pending.submit">
                <template slot="default">
                    <form class="form" @submit.prevent="submitEvent">
                        <table class="input-table">
                            <tbody>
                            <tr :class="{error: val.error.name}" id="form-name">
                                <td>名称</td>
                                <td>
                                    <input type="text" v-model="form.name" @input="val.error.name=''" class="form-text">
                                    <span class="msg"></span>
                                    <span class="need">*</span>
                                    <span class="e-msg"></span>
                                </td>
                            </tr>
                            <tr :class="{error: val.error.weight}" id="form-weight">
                                <td>权重</td>
                                <td>
                                    <input type="number" v-model="form.weight" @input="val.error.weight = ''" class="form-text">
                                    <span class="msg">仅允许整数</span>
                                    <span class="need"></span>
                                    <span class="e-msg"></span>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td colspan="2">
                                    <button type="submit"></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </template>
                <template slot="footer">
                    <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent">确认</Button>
                    <Button v-ripple type="error" @click="closeFormModal">关闭</Button>
                </template>
            </my-form-modal>

            <my-form-drawer v-model="val.drawer">
                <template slot="header">
                    <div class="run-action-title">
                        <div class="left">权限分配</div>
                        <div class="right">
                            <Button v-ripple type="primary" :loading="val.pending.allocatePermission" @click="allocatePermission">提交</Button>
                            <Button v-ripple type="error" @click="closeFormDrawer">关闭</Button>
                        </div>
                    </div>
                </template>
                <template slot="default">
                    <Tree :data="permission" :show-checkbox="true" :check-strictly="true" @on-select-change="permissionSelectedEvent" @on-check-change="permissionCheckedEvent" :multiple="true"></Tree>
                </template>
            </my-form-drawer>
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