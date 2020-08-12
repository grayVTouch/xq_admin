<template>
    <my-base ref="base">
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
                            <div class="field">昵称：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.nickname"></div>
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
                            <div class="field"></div>
                            <div class="value">
                                <button type="submit" v-show="false"></button>
                                <Button v-ripple type="primary" :loading="val.pending.getData" @click="searchEvent"><my-icon icon="search" mode="right" />搜索</Button>
                            </div>
                        </div>
                    </form>
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
                    <div class="left">数据列表</div>
                    <div class="right"></div>
                </div>

                <div class="table">

                    <Table border :height="$store.state.context.table.height" :columns="table.field" :data="table.data" @on-selection-change="selectedEvent" :loading="val.pending.getData">
                        <template v-slot:avatar="{row,index}">
                            <img :src="row.avatar ? row.__avatar__ : $store.state.context.res.notFound" @click="link(row.__avatar__ , '__blank')" :height="$store.state.context.table.imageH" class="image">
                        </template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
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

            <my-form-modal v-model="val.modal" :title="title" :loading="val.pending.submit" @on-ok="submitEvent" @on-cancel="closeFormModal">
                <template slot="default">
                    <form class="form" @submit.prevent="submitEvent">
                        <table class="input-table">
                            <tbody>

                            <tr :class="{error: val.error.username}">
                                <td>名称</td>
                                <td>
                                    <input type="text" v-model="form.username" @input="val.error.username=''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.username }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.password}">
                                <td>密码</td>
                                <td>
                                    <input type="text" v-model="form.password" @input="val.error.password=''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">为空，则使用原密码</div>
                                    <div class="e-msg">{{ val.error.password }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.avatar}">
                                <td>封面</td>
                                <td>
                                    <div ref="avatar">
                                        <!-- 上传图片组件 -->
                                        <div class='uploader'>
                                            <div class="upload">
                                                <div class="handler">

                                                    <div class="line input hide">
                                                        <input type="file" class="file-input">
                                                    </div>

                                                    <div class="line icon">
                                                        <div class="ico">
                                                            <div class="feedback run-action-feedback-round"><i class="iconfont run-iconfont run-iconfont-shangchuan"></i></div>
                                                            <div class="clear run-action-feedback-round" title="清空"><i class="iconfont run-iconfont run-iconfont-qingkong"></i></div>
                                                        </div>
                                                        <div class="text">请选择要上传的文件</div>
                                                    </div>

                                                </div>

                                                <div class="msg"></div>
                                            </div>
                                            <div class="preview"></div>

                                        </div>
                                    </div>

                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.thumb }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.sex}">
                                <td>性别</td>
                                <td>
                                    <RadioGroup v-model="form.sex">
                                        <Radio v-for="(v,k) in $store.state.business.user.sex" :key="k" :label="k">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.sex }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.birthday}">
                                <td>生日</td>
                                <td>
                                    <DatePicker v-model="val.birthday" format="yyyy-MM-dd" class="iview-form-input" @on-change="setDate"></DatePicker>
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.birthday }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.phone}">
                                <td>手机号码</td>
                                <td>
                                    <input type="text" v-model="form.phone" @input="val.error.phone=''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.phone }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.email}">
                                <td>电子邮件</td>
                                <td>
                                    <input type="text" v-model="form.email" @input="val.error.email=''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.email }}</div>
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