<template>
        <div class="view-item">

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
                            <div class="field">用户id：</div>
                            <div class="value"><input type="text" class="form-text" v-model="search.user_id"></div>
                        </div>

                        <div class="option">
                            <div class="field">模块：</div>
                            <div class="value">
                                <my-select :data="modules" v-model="search.module_id" empty="" @change="getCategories"></my-select>
                                <my-loading v-if="myValue.pending.getModules"></my-loading>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">分类：</div>
                            <div class="value">
                                <my-deep-select :data="categories" v-model="search.category_id" :has="false" empty=""></my-deep-select>
                                <my-loading v-if="myValue.pending.getCategories"></my-loading>
                                <span class="msg">请选择模块后操作</span>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">类型：</div>
                            <div class="value">
                                <i-radio-group v-model="search.type">
                                    <i-radio v-for="(v,k) in TopContext.business.imageProject.type" :key="k" :label="k">{{ v }}</i-radio>
                                </i-radio-group>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">关联主体id：</div>
                            <div class="value">
                                <input type="number" class="form-text" v-model="search.image_subject_id" />
                            </div>
                        </div>

                        <div class="option">
                            <div class="field">状态：</div>
                            <div class="value">
                                <i-radio-group v-model="search.status">
                                    <i-radio v-for="(v,k) in TopContext.business.imageProject.status" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
                            </div>
                        </div>

                        <div class="option">
                            <div class="field"></div>
                            <div class="value">
                                <button type="submit" v-show="false"></button>
                                <my-table-button @click="searchEvent"><my-icon icon="search" mode="right" />搜索</my-table-button>
                                <my-table-button @click="resetEvent" class="m-l-10"><my-icon icon="reset" mode="right" />重置</my-table-button>
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
                        <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
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

                    <i-table
                            ref="table"
                            class="w-r-100"
                            border
                            :height="TopContext.table.height"
                            :columns="table.field"
                            :data="table.data"
                            :loading="myValue.pending.getData"
                            @on-selection-change="selectionChangeEvent"
                            @on-row-dblclick="editEvent"
                            @on-row-click="rowClickEvent"
                            @on-sort-change="sortChangeEvent"
                    >
                        <template v-slot:thumb="{row,index}">
                            <my-table-preview :src="row.thumb"></my-table-preview>
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
                        <template v-slot:image_subject_id="{row,index}">
                            {{ row.type === 'pro' ? (row.image_subject ? `${row.image_subject.name}【${row.image_subject.id}】` : `unknow【${row.image_subject_id}】`) : null }}
                        </template>

                        <template v-slot:status="{row,index}">
                            <b :class="{'run-red': row.status === -1 , 'run-gray': row.status === 0 , 'run-green': row.status === 1}">{{ row.__status__ }}</b>
                        </template>

                        <template v-slot:images="{row,index}">
<!--                            <i-poptip placement="right" width="400" title="图片列表" :transfer="true" trigger="hover">-->
                                <i-button>{{ row.images.length }}P</i-button>
<!--                                <div slot="content">-->
<!--                                    <table class="line-table">-->
<!--                                        <tbody>-->
<!--                                        <tr v-for="v in row.images" :key="v.id">-->
<!--                                            <td><img :src="v.path ? v.path : TopContext.res.notFound" @click="openWindow(v.path , '__blank')" class="image"></td>-->
<!--                                        </tr>-->
<!--                                        </tbody>-->
<!--                                    </table>-->
<!--                                </div>-->
<!--                            </i-poptip>-->
                        </template>

                        <template v-slot:tags="{row,index}">
                            <i-poptip placement="right" width="400" title="标签" :transfer="true" trigger="hover">
                                <i-button>悬浮可查看详情</i-button>
                                <div slot="content">
                                    <table class="line-table">
                                        <tbody>
                                        <tr v-for="v in row.tags" :key="v.id">
                                            <td>{{ v.name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </i-poptip>
                        </template>

                    </i-table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
                <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
            </div>

            <div class="line page">
<!--                <Page :total="table.total" :page-size="TopContext.limit" :current="table.page" :show-total="true" :show-sizer="false" :show-elevator="true"  @on-change="pageEvent" />-->
                <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
            </div>

            <my-form ref="form" :id="current.id" :mode="myValue.mode" @on-success="getData"></my-form>

        </div>
</template>

<script src="./js/index.js"></script>

<style src="../public/css/base.css"></style>
<style scoped>

</style>
