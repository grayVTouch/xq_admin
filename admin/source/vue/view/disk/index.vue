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
                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showBatchBtn"><my-icon icon="shanchu" />删除选中项 （{{ val.selectedIds.length }}）</my-table-button>
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

                    <Table border :height="$store.state.context.table.height" :columns="table.field" :data="table.data" @on-selection-change="selectedEvent" :loading="val.pending.getData">
                        <template v-slot:default="{row,index}"><my-switch v-model="row.default" :loading="val.pending['default_' + row.id]" :extra="{id: row.id , field: 'default'}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button type="error" :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </Table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showBatchBtn"><my-icon icon="shanchu" />删除选中项 （{{ val.selectedIds.length }}）</my-table-button>
            </div>

            <div class="line page">
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form-modal v-model="val.modal" :title="title" :loading="val.pending.submit" @on-ok="submitEvent" @on-cancel="closeFormModal">
                <template slot="default">
                    <form class="form" @submit.prevent="submitEvent">
                        <table class="input-table">
                            <tbody>
                            <tr :class="{error: val.error.path}">
                                <td>目录真实路径</td>
                                <td>
                                    <input type="text" v-model="form.path" @input="val.error.path = ''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg">请特别注意</div>
                                    <div class="e-msg">{{ val.error.path }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.prefix}">
                                <td>路径前缀</td>
                                <td>
                                    <input type="text" v-model="form.prefix" @input="val.error.prefix = ''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg">例如：upload</div>
                                    <div class="e-msg">{{ val.error.prefix }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.os}">
                                <td>操作系统</td>
                                <td>
                                    <RadioGroup v-model="form.os"  @input="val.error.os = ''">
                                        <Radio v-for="(v,k) in $store.state.business.disk.os" :key="k" :label="k">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.os }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.default}">
                                <td>默认？</td>
                                <td>
                                    <RadioGroup v-model="form.default"  @input="val.error.default = ''">
                                        <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：否</div>
                                    <div class="e-msg">{{ val.error.default }}</div>
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