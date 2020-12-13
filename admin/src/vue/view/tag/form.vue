<template>
    <div>
        <my-form-modal
                v-model="val.show"
                :title="title"
                :loading="val.pending.submitEvent"
                @on-ok="submitEvent"
                @on-cancel="closeFormModal"
        >
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
                                <input type="text" readonly="readonly" :value="`${owner.username}【${owner.id}】`" class="form-text w-150 run-cursor-not-allow">
                                如需重新搜索，请点击
                                <i-button @click="showUserSelector">搜索</i-button>
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.user_id }}</div>
                            </td>
                        </tr>

                        <tr :class="{error: val.error.module_id}">
                            <td>所属模块</td>
                            <td>
                                <my-select :data="modules" v-model="form.module_id" @change="val.error.module_id = ''" :width="TopContext.style.inputItemW"></my-select>
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.module_id }}</div>
                            </td>
                        </tr>

                        <tr :class="{error: val.error.status}">
                            <td>状态</td>
                            <td>
                                <i-radio-group v-model="form.status">
                                    <i-radio v-for="(v,k) in TopContext.business.imageSubject.status" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
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
                <i-button v-ripple type="primary" :loading="val.pending.submitEvent" @click="submitEvent">确认</i-button>
                <i-button v-ripple type="error" @click="closeFormModal">关闭</i-button>
            </template>
        </my-form-modal>

        <my-user-selector ref="user-selector" @on-change="userChangeEvent"></my-user-selector>
    </div>
</template>

<script src="./js/form.js"></script>

<style scoped>

</style>
