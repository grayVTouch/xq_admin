<template>
    <my-base ref="base">
        <div class="mask">

            <div class="line search hide"></div>

            <div class="line data">

                <div class="run-title">
                    <div class="left">
                        数据列表&nbsp;&nbsp;&nbsp;

                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                        <my-table-button type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>

                    </div>
                    <div class="right"></div>
                </div>

                <div class="table">

                    <i-table border width="100%" :height="630" :columns="table.field" :data="table.data" @on-selection-change="selectionChangeEvent" :loading="myValue.pending.getData">
                        <template v-slot:name="{row,index}">
                            <template v-if="row.floor > 1">{{ '|' + '_'.repeat((row.floor - 1) * 10) + row.name + `【${row.module ? row.module.name : 'unknow'}】` }}</template>
                            <template v-else>{{ row.name + `【${row.module ? row.module.name : 'unknow'}】` }}</template>
                        </template>
<!--                        <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>-->
                        <template v-slot:enable="{row,index}"><my-switch v-model="row.enable" :loading="myValue.pending['enable_' + row.id]" :extra="{id: row.id , field: 'enable'}" @on-change="updateBoolValEvent" /></template>
                        <template v-slot:is_menu="{row,index}"><my-switch v-model="row.is_menu" :loading="myValue.pending['is_menu_' + row.id]" :extra="{id: row.id , field: 'is_menu'}" @on-change="updateBoolValEvent" /></template>

                        <template v-slot:action="{row , index}">
                            <my-table-button @click="editEvent(row)"><my-icon icon="edit" />编辑</my-table-button>
                            <my-table-button type="error" :loading="myValue.pending['delete_' + row.id]" @click="destroyEvent(index , row)"><my-icon icon="shanchu" />删除</my-table-button>
                        </template>
                    </i-table>

                </div>

            </div>

            <div class="line operation">
                <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                <my-table-button type="error" @click="destroyAllEvent" :loading="myValue.pending.destroyAll"><my-icon icon="shanchu" />删除选中项 <span v-if="selection.length > 0">（{{ selection.length }}）</span></my-table-button>
            </div>

            <div class="line page hide"></div>

            <my-form-modal v-model="myValue.modal" :title="title">
                <template slot="default">
                    <form class="form" @submit.prevent="submitEvent" ref="form">
                        <table class="input-table">
                            <tbody>
                            <tr :class="{error: myValue.error.name}">
                                <td>名称</td>
                                <td>
                                    <input type="text" v-model="form.name"  @input="myValue.error.name = ''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ myValue.error.name }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.module_id}">
                                <td>所属模块</td>
                                <td>
                                    <my-select :data="modules" v-model="form.module_id" @change="moduleChangedEvent"></my-select>
                                    <my-loading v-if="myValue.pending.getModules"></my-loading>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ myValue.error.module_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.p_id}">
                                <td>上级导航菜单</td>
                                <td>
                                    <my-deep-select :data="navs" v-model="form.p_id" :has="true" :attr="myValue.attr"  @change="myValue.error.p_id = ''"></my-deep-select>
                                    <my-loading v-if="myValue.pending.getNavs"></my-loading>
                                    <span class="need">*</span>
                                    <div class="msg">请务必选择模块后操作</div>
                                    <div class="e-msg">{{ myValue.error.p_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.value}">
                                <td>value</td>
                                <td>
                                    <input type="text" v-model="form.value" class="form-text" @input="myValue.error.value = ''">
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ myValue.error.value }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.is_menu}">
                                <td>菜单？</td>
                                <td>
                                    <i-radio-group v-model="form.is_menu"  @input="myValue.error.is_menu = ''">
                                        <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                    </i-radio-group>
                                    <span class="need">*</span>
                                    <div class="msg">默认：否</div>
                                    <div class="e-msg">{{ myValue.error.is_menu }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.enable}">
                                <td>启用？</td>
                                <td>
                                    <i-radio-group v-model="form.enable"  @input="myValue.error.enable = ''">
                                        <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                    </i-radio-group>
                                    <span class="need">*</span>
                                    <div class="msg">默认：开启</div>
                                    <div class="e-msg">{{ myValue.error.enable }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.platform}">
                                <td>所属平台</td>
                                <td>
                                    <select v-model="form.platform" class="form-select">
                                        <option value="">请选择...</option>
                                        <option v-for="(v,k) in TopContext.business.platform" :key="k" :value="k">{{ v }}</option>
                                    </select>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ myValue.error.platform }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: myValue.error.weight}">
                                <td>权重</td>
                                <td>
                                    <input type="number" class="form-text"  @input="myValue.error.weight = ''" v-model="form.weight">
                                    <span class="need"></span>
                                    <div class="msg">请提供整数</div>
                                    <div class="e-msg">{{ myValue.error.weight }}</div>
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
                    <i-button v-ripple type="primary" :loading="myValue.pending.submitEvent" @click="submitEvent">确认</i-button>
                    <i-button v-ripple type="error" @click="closeFormModal">关闭</i-button>
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
