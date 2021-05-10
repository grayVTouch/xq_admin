<template>

    <my-base>
        <template slot="search">
            <my-search-form @submit="searchEvent">

                <my-search-form-item name="id">
                    <input type="text" class="form-text" v-model="search.id" />
                </my-search-form-item>

                <my-search-form-item name="名称">
                    <input type="text" class="form-text" v-model="search.name" />
                </my-search-form-item>

                <my-search-form-item name="用户id">
                    <input type="text" class="form-text" v-model="search.user_id" />
                </my-search-form-item>

                <my-search-form-item name="模块">
                    <my-select :data="modules" v-model="search.module_id" empty="" @change="getCategories"></my-select>
                    <my-loading v-if="myValue.pending.getModules"></my-loading>
                </my-search-form-item>

                <my-search-form-item name="分类">
                    <my-deep-select :data="categories" v-model="search.category_id" :has="false" empty=""></my-deep-select>
                    <my-loading v-if="myValue.pending.getCategories"></my-loading>
                    <span class="msg">请选择模块后操作</span>
                </my-search-form-item>

                <my-search-form-item name="类型">
                    <i-radio-group v-model="search.type">
                        <i-radio v-for="(v,k) in TopContext.business.imageProject.type" :key="k" :label="k">{{ v }}</i-radio>
                    </i-radio-group>
                </my-search-form-item>

                <my-search-form-item name="关联主体id">
                    <input type="text" class="form-text" v-model="search.image_subject_id" />
                </my-search-form-item>

                <my-search-form-item name="状态">
                    <i-radio-group v-model="search.status">
                        <i-radio v-for="(v,k) in TopContext.business.imageProject.status" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                    </i-radio-group>
                </my-search-form-item>

                <my-search-form-item :show-separator="false">
                    <my-table-button @click="searchEvent"><my-icon icon="search" mode="right" />搜索</my-table-button>
                    <my-table-button @click="resetEvent" class="m-l-10"><my-icon icon="reset" mode="right" />重置</my-table-button>
                </my-search-form-item>
            </my-search-form>
        </template>

        <template slot="action">
            <my-table-button class="m-r-10" @click="addEvent"><my-icon icon="add" />添加</my-table-button>
            <my-table-button class="m-r-10" @click="editEventByButton"><my-icon icon="edit" />编辑</my-table-button>
            <my-table-button class="m-r-10" type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
        </template>

        <template slot="page">
            <my-page :total="table.total" :limit="table.limit" :page="table.page" @on-change="pageEvent"></my-page>
        </template>

        <template slot="table">
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
                    <my-table-image-preview :src="row.thumb"></my-table-image-preview>
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

        </template>

        <my-form ref="form" :id="current.id" :mode="myValue.mode" @on-success="getData"></my-form>

    </my-base>

</template>

<script src="./js/index.js"></script>

<style src="../public/css/base.css"></style>
<style scoped>

</style>
