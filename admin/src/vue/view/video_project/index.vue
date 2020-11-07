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
                            <div class="field">名称：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.name"></div>
                        </div>

                        <div class="option">
                            <div class="field">模块：</div>
                            <div class="value">
                                <my-select :data="modules" empty="" v-model="search.module_id"></my-select>
                                <my-loading v-if="val.pending.getModules"></my-loading>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">视频系列id：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.video_series_id"></div>
                        </div>

                        <div class="option">
                            <div class="field">视频制作公司id：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.video_company_id"></div>
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
                        <template v-slot:name="{row,index}">{{ row.name }}</template>
                        <template v-slot:thumb="{row,index}">
                            <Poptip trigger="hover" placement="right" :transfer="true">
                                <img :src="row.thumb ? row.thumb : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image" @click="link(row.thumb)" alt="">
                                <div slot="content" class="table-preview-image-style">
                                    <img :src="row.thumb ? row.thumb : $store.state.context.res.notFound" class="image" @click="link(row.thumb)" alt="">
                                </div>
                            </Poptip>
                        </template>
                        <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                        <template v-slot:category_id="{row,index}">{{ row.category ? `${row.category.name}【${row.category.id}】` : `unknow【${row.category_id}】` }}</template>
                        <template v-slot:video_series_id="{row,index}">{{ row.video_series ? `${row.video_series.name}【${row.video_series.id}】` : `unknow【${row.video_series_id}】` }}</template>
                        <template v-slot:video_company_id="{row,index}">{{ row.video_company ? `${row.video_company.name}【${row.video_company.id}】` : `unknow【${row.video_company_id}】` }}</template>
                        <template v-slot:user_id="{row,index}">{{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}</template>
                        <template v-slot:status="{row,index}"><b :class="{'run-red': row.status === -1 , 'run-gray': row.status === 0 , 'run-green': row.status === 1}">{{ row.__status__ }}</b></template>
                        <template v-slot:tags="{row,index}">
                            <Poptip placement="right" width="400" title="标签" :transfer="true" trigger="hover">
                                <Button>悬浮可查看详情</Button>
                                <div slot="content">
                                    <table class="line-table">
                                        <tbody>
                                        <tr v-for="v in row.tags" :key="v.id">
                                            <td>{{ v.name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </Poptip>
                        </template>
                        <template v-slot:status="{row,index}">
                            <span class="run-weight" :class="{'run-red': row.status == 'making' , 'run-green': row.status === 'completed' , 'run-gray': row.status === 'terminated'}">{{ row.__status__ }}</span>
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
                <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="val.selectedIds.length > 0">（{{ val.selectedIds.length }}）</span></my-table-button>
            </div>

            <div class="line page">
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form ref="form" :mode="val.mode" :data="form" @on-success="getData"></my-form>

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
