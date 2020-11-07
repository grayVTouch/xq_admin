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
                        <div class="field">模块：</div>
                        <div class="value">
                            <my-select :data="modules" v-model="search.module_id" empty=""></my-select>
                            <my-loading v-if="val.pending.getModules"></my-loading>
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

                    <Table border :height="$store.state.context.table.height" :columns="table.field" :data="table.data" @on-selection-change="selectionChangeEvent" :loading="val.pending.getData" @on-row-dblclick="editEvent">
<!--                        <template v-slot:name="{row,index}">{{ row.name + `【${row.module ? row.module.name : 'unknow'}】` }}</template>-->
                        <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                        <template v-slot:user_id="{row,index}">{{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}</template>
                        <template v-slot:status="{row,index}"><b :class="{'run-red': row.status === -1 , 'run-gray': row.status === 0 , 'run-green': row.status === 1}">{{ row.__status__ }}</b></template>
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

                            <tr :class="{error: val.error.user_id}">
                                <td>所属用户：</td>
                                <td>
                                    <input type="text" readonly="readonly" :value="`${getUsername(users.current.username , users.current.nickname)}【${users.current.id}】`" class="form-text w-150 run-cursor-not-allow">
                                    如需重新搜索，请点击
                                    <Button @click="searchUserEvent">搜索</Button>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.user_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.module_id}">
                                <td>所属模块</td>
                                <td>
                                    <my-select :data="modules" v-model="form.module_id" @change="val.error.module_id = ''"></my-select>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.module_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.status}">
                                <td>状态</td>
                                <td>
                                    <RadioGroup v-model="form.status">
                                        <Radio v-for="(v,k) in $store.state.business.image_subject.status" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：待审核</div>
                                    <div class="e-msg">{{ val.error.status }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.fail_reason}" v-show="form.status === -1">
                                <td>失败原因</td>
                                <td>
                                    <textarea v-model="form.fail_reason" class="form-textarea" @input="val.error.fail_reason = ''"></textarea>
                                    <span class="need">*</span>
                                    <div class="msg">当状态为审核失败的时候必填</div>
                                    <div class="e-msg">{{ val.error.fail_reason }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.weight}">
                                <td>权重</td>
                                <td>
                                    <input type="number" v-model="form.weight" @input="val.error.weight = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.weight }}</div>
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

        <!-- 请选择用户 -->
        <my-form-modal v-model="val.modalForUser" title="请选择用户" :width="1000">
            <template slot="footer">
                <Button v-ripple type="error" @click="val.modalForUser=false">取消</Button>
            </template>
            <template slot="default">
                <div class="search-modal">
                    <div class="input">
                        <div class="input-mask"><input type="text" v-model="users.value" @keyup.enter="searchUser" placeholder="请输入搜索值"></div>
                        <div class="msg">输入id、用户名、手机号码、邮箱可查询</div>
                    </div>
                    <div class="list">
                        <Table border :loading="val.pending.searchUser" :data="users.data" :columns="users.field" @on-row-click="updateUserEvent">
                            <template v-slot:avatar="{row,index}"><img :src="row.avatar ? row.avatar : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                            <template v-slot:action="{row,index}"><my-table-button>选择</my-table-button></template>
                        </Table>
                    </div>
                    <div class="pager">
                        <my-page :total="users.total" :limit="users.limit" :page="users.page" @on-change="userPageEvent"></my-page>
                    </div>
                </div>
            </template>
        </my-form-modal>

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
