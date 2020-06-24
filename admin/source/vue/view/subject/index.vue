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
                            <Button type="primary" :loading="val.pending.getData" @click="searchEvent"><my-icon icon="search" mode="right" />搜索</Button>
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
                            <Button type="primary" :loading="val.pending.getData" @click="searchEvent"><my-icon icon="search" mode="right" />搜索</Button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="line">
                <div class="run-action-title">
                    <div class="left">
                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项</my-table-button>
                    </div>
                    <div class="right">
                        <Page :total="table.total" :page-size="$store.state.context.limit" :current="table.page" :show-total="true" :show-sizer="false" :show-elevator="true"  @on-change="pageEvent" />
                    </div>
                </div>
            </div>

            <div class="line data">

                <div class="run-title">
                    <div class="left">数据列表</div>
                    <div class="right"></div>
                </div>

                <div class="table">

                    <Table border :columns="table.field" :data="table.data" @on-selection-change="selectedEvent">
                        <template v-slot:thumb="{row,index}"><img :src="row.thumb ? row.__thumb__ : $store.state.context.res.notFound" class="image" height="40" @click.stop="link(row.thumb)"></template>
                        <template v-slot:attr="{row,index}">
                            <Poptip placement="right" width="400" title="关联主体属性" :transfer="true" trigger="hover">
                                <Button>悬浮可查看详情</Button>
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
                            </Poptip>
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
                <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项</my-table-button>
            </div>

            <div class="line page">
                <Page :total="table.total" :page-size="$store.state.context.limit" :current="table.page" :show-total="true" :show-sizer="false" :show-elevator="true"  @on-change="pageEvent" />
            </div>

            <my-form-drawer v-model="val.drawer" :title="title" :loading="val.pending.submit" @on-ok="submitEvent" @on-cancel="closeFormDrawer">

                <div class="run-action-title" slot="header">
                    <div class="left">{{ title }}</div>
                    <div class="right">
                        <Button type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</Button>
                        <Button type="error" @click="closeFormDrawer"><my-icon icon="guanbi" />关闭</Button>
                    </div>
                </div>

                <form class="form subject-form" @submit.prevent slot="default">
                    <table class="input-table">
                        <tbody>

                        <tr :class="getClass(val.error.name)" id="form-name">
                            <td>名称</td>
                            <td>
                                <input type="text" v-model="form.name" @input="val.error.name=''" class="form-text">
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg"></div>
                            </td>
                        </tr>

                        <tr :class="getClass(val.error.thumb)" id="form-thumb">
                            <td>封面</td>
                            <td>
                                <div ref="thumb">
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

                        <tr :class="getClass(val.error.attr)" id="form-attr">
                            <td>属性</td>
                            <td>
                                <div class="attr">
                                    <div class="line">
                                        <table class="line-table">
                                            <thead>
                                            <tr>
                                                <th>字段</th>
                                                <th>值</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(v,k) in attr" :key="k">
                                                <td><input type="text" v-model="v.field" class="form-text"></td>
                                                <td><input type="text" v-model="v.value" class="form-text"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="line">
                                        <my-table-button @click="attr.push({field: '' , value: ''})"><my-icon icon="add" />添加</my-table-button>
                                        <my-table-button type="error" @click="attr.pop()"><my-icon icon="delete" />减少</my-table-button>
                                    </div>
                                </div>
                                <span class="need"></span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.attr }}</div>
                            </td>
                        </tr>

                        <tr :class="getClass(val.error.description)" id="form-description">
                            <td>描述</td>
                            <td>
                                <textarea v-model="form.description" @input="val.error.description=''" class="form-textarea"></textarea>
                                <span class="need"></span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.description }}</div>
                            </td>
                        </tr>

                        <tr :class="getClass(val.error.weight)" id="form-weight">
                            <td>权重</td>
                            <td>
                                <input type="number" v-model="form.weight" @input="val.error.weight = ''" class="form-text">
                                <span class="need"></span>
                                <div class="msg">仅允许整数</div>
                                <div class="e-msg">{{ val.error.thumb }}</div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </form>

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

    /**
     * **************
     * 表单
     * **************
     */
    .subject-form .attr {

    }

    .subject-form .attr .line {
        margin-bottom: 10px;
    }

    .subject-form .attr .line:nth-last-of-type(1) {
        margin-bottom: 0;
    }

    .subject-form .attr .form-text {
        min-width: auto;
        width: 100%;
    }
</style>