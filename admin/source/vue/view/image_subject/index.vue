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
                        <template v-slot:thumb="{row,index}">
                            <img :src="row.thumb ? row.__thumb__ : $store.state.context.res.notFound" height="40" class="image">
                        </template>
                        <template v-slot:user_id="{row,index}">
                            {{ row.user ? `${row.user.username}【${row.user.id}】` : `unknow【${row.user_id}】` }}
                        </template>
                        <template v-slot:module_id="{row,index}">
                            {{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}
                        </template>
                        <template v-slot:category_id="{row,index}">
                            {{ row.category ? `${row.category.name}【${row.category.id}】` : `unknow【${row.category_id}】` }}
                        </template>
                        <template v-slot:subject_id="{row,index}">
                            {{ row.type === 'pro' ? (row.subject ? `${row.subject.name}【${row.subject.id}】` : `unknow【${row.subject_id}】`) : null }}
                        </template>

                        <template v-slot:status="{row,index}">
                            <b :class="{red: row.status === -1 , gray: row.status === 0 , green: row.status === 1}">{{ row.__status__ }}</b>
                        </template>

                        <template v-slot:images="{row,index}">
                            <Poptip placement="right" width="400" title="图片列表" :transfer="true" trigger="hover">
                                <Button>{{ row.images.length }}P</Button>
                                <div slot="content">
                                    <table class="line-table">
                                        <tbody>
                                        <tr v-for="v in row.images" :key="v.id">
                                            <td><img :src="v.path ? v.__path__ : $store.state.context.res.notFound" @click="link(v.__path__ , '__blank')" class="image"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </Poptip>
                        </template>

                        <template v-slot:tag="{row,index}">
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

            <my-form
                    :form="form"
                    :title="title"
                    :categories="categories"
                    :modules="modules"
                    :topTags="topTags"
                    :mode="val.mode"
                    v-model="val.drawer"
                    @on-module-change="moduleChanged"
                    @on-success="getData"
            ></my-form>

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