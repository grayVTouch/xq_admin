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
                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="val.selectedIds.length > 0">（{{ val.selectedIds.length }}）</span></my-table-button>
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

                    <Table border :height="$store.state.context.table.height" :columns="table.field" :data="table.data" @on-selection-change="selectionChangeEvent" :loading="val.pending.getData">
                        <template v-slot:enable="{row,index}"><my-switch v-model="row.is_enabled" :loading="val.pending['enable_' + row.id]" :extra="{field: 'enable' , data: row}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:default="{row,index}"><my-switch v-model="row.is_default" :loading="val.pending['default_' + row.id]" :extra="{field: 'default' , data: row}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:auth="{row,index}"><my-switch v-model="row.auth" :loading="val.pending['auth_' + row.id]" :extra="{field: 'auth' , data: row}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button type="error" :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </Table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="val.selectedIds.length > 0">（{{ val.selectedIds.length }}）</span></my-table-button>
            </div>

            <div class="line page">
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form-modal v-model="val.modal" :title="title" :loading="val.pending.submit" @on-ok="submitEvent" @on-cancel="closeFormModal">
                <template slot="default">
                    <form class="form" @submit.prevent="submitEvent">
                        <table class="input-table">
                            <tbody>
                            <tr :class="{error: val.error.name}">
                                <td>名称</td>
                                <td>
                                    <input type="text" v-model="form.name" @input="val.error.name=''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.name }}</div>
                                </td>
                            </tr>
                            <tr :class="{error: val.error.description}">
                                <td>描述</td>
                                <td>
                                    <textarea v-model="form.description" @input="val.error.description=''" class="form-textarea"></textarea>
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.description }}</div>
                                </td>
                            </tr>
                            <tr :class="{error: val.error.is_enabled}">
                                <td>启用？</td>
                                <td>
                                    <RadioGroup v-model="form.is_enabled"  @input="val.error.is_enabled = ''">
                                        <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：开启</div>
                                    <div class="e-msg">{{ val.error.is_enabled }}</div>
                                </td>
                            </tr>
                            <tr :class="{error: val.error.is_default}">
                                <td>默认？</td>
                                <td>
                                    <RadioGroup v-model="form.is_default"  @input="val.error.is_default = ''">
                                        <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：否</div>
                                    <div class="e-msg">{{ val.error.is_default }}</div>
                                </td>
                            </tr>
                            <tr :class="{error: val.error.is_auth}">
                                <td>认证？</td>
                                <td>
                                    <RadioGroup v-model="form.is_auth"  @input="val.error.is_auth = ''">
                                        <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：否</div>
                                    <div class="e-msg">{{ val.error.auth }}</div>
                                </td>
                            </tr>
                            <tr :class="{error: val.error.weight}">
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
