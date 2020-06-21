<template>
    <my-base ref="base">
        <div class="mask">

            <div class="line search hide"></div>

            <div class="line data">

                <div class="run-title">
                    <div class="left">
                        数据列表&nbsp;&nbsp;&nbsp;

                        <my-table-button @click="addEvent"><my-icon icon="add" />添加</my-table-button>
                        <my-table-button type="error" @click="destroyAllEvent" :loading="val.pending.destroyAll" v-show="showDestroyAllBtn"><my-icon icon="shanchu" />删除选中项</my-table-button>
                    </div>
                    <div class="right"></div>
                </div>

                <div class="table">

                    <Table border width="100%" height="600" :columns="table.field" :data="table.data" @on-selection-change="selectedEvent">
                        <template v-slot:name="{row,index}">
                            <template v-if="row.floor > 1">{{ '|' + '_'.repeat((row.floor - 1) * 4) + row.name }}</template>
                            <template v-else>{{ row.name }}</template>
                        </template>
                        <template v-slot:enable="{row,index}"><my-switch v-model="row.enable" :loading="val.pending['enable_' + row.id]" :extra="{id: row.id , field: 'enable'}" @on-change="updateBoolValEvent" /></template>
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

            <div class="line page hide"></div>

            <my-form-modal v-model="val.modal" :title="modalTitle">
                <template slot="default">
                    <form class="form" @submit.prevent ref="form">
                        <table class="input-table">
                            <tbody>
                            <tr :class="getClass(val.error.name)" id="form_cn">
                                <td>名称</td>
                                <td>
                                    <input type="text" v-model="form.name"  @input="val.error.name = ''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.name }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.p_id)" id="form_p_id">
                                <td>上级权限</td>
                                <td>
                                    <my-select :data="category" v-model="form.p_id" :has="true" :attr="val.attr"  @change="val.error.p_id = ''"></my-select>
                                    <span class="msg"></span>
                                    <span class="need">*</span>
                                    <span class="e-msg">{{ val.error.p_id }}</span>
                                </td>
                            </tr>
                            <tr :class="getClass(val.error.description)" id="form_description">
                                <td>描述</td>
                                <td>
                                    <textarea v-model="form.description" class="form-textarea" @input="val.error.description = ''"></textarea>
                                    <span class="msg"></span>
                                    <span class="need"></span>
                                    <span class="e-msg">{{ val.error.description }}</span>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.enable)" id="form_enable">
                                <td>启用？</td>
                                <td>
                                    <RadioGroup v-model="form.enable"  @input="val.error.enable = ''">
                                        <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="msg">默认：开启</span>
                                    <span class="need">*</span>
                                    <span class="e-msg">{{ val.error.enable }}</span>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.weight)" id="form_weight">
                                <td>权重</td>
                                <td>
                                    <input type="number" class="form-text"  @input="val.error.weight = ''" v-model="form.weight">
                                    <span class="msg">请提供整数</span>
                                    <span class="need"></span>
                                    <span class="e-msg">{{ val.error.weight }}</span>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </form>
                </template>
                <template slot="footer">
                    <Button type="primary" :loading="val.pending.submit" @click="submitEvent">确认</Button>
                    <Button type="error" @click="closeFormModal">关闭</Button>
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