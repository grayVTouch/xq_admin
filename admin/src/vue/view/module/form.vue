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
                        <tr :class="{error: val.error.description}">
                            <td>描述</td>
                            <td>
                                <textarea v-model="form.description" @input="val.error.description=''" class="form-textarea"></textarea>
                                <span class="need"></span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.description }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.is_enabled}">
                            <td>启用？</td>
                            <td>
                                <i-radio-group v-model="form.is_enabled"  @input="val.error.is_enabled = ''">
                                    <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
                                <span class="need">*</span>
                                <div class="msg">默认：开启</div>
                                <div class="e-msg">{{ val.error.is_enabled }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.is_default}">
                            <td>默认？</td>
                            <td>
                                <i-radio-group v-model="form.is_default"  @input="val.error.is_default = ''">
                                    <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
                                <span class="need">*</span>
                                <div class="msg">默认：否</div>
                                <div class="e-msg">{{ val.error.is_default }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.is_auth}">
                            <td>认证？</td>
                            <td>
                                <i-radio-group v-model="form.is_auth"  @input="val.error.is_auth = ''">
                                    <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
                                <span class="need">*</span>
                                <div class="msg">默认：否</div>
                                <div class="e-msg">{{ val.error.auth }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.weight}">
                            <td>权重</td>
                            <td>
                                <input type="number" v-model="form.weight" @input="val.error.weight = ''" class="form-text">
                                <span class="msg">仅允许整数</span>
                                <span class="need"></span>
                                <span class="e-msg"></span>
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
