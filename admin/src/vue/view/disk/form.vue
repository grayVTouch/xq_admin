<template>
    <div>
        <my-form-modal
                v-model="val.show"
                :title="title"
                :loading="val.pending.submit"
                @on-ok="submitEvent"
                @on-cancel="closeFormModal"
        >
            <template slot="default">
                <form class="form" @submit.prevent="submitEvent">
                    <table class="input-table">
                        <tbody>
                        <tr :class="{error: val.error.path}">
                            <td>目录真实路径</td>
                            <td>
                                <input type="text" v-model="form.path" @input="val.error.path = ''" class="form-text">
                                <span class="need">*</span>
                                <div class="msg">例如：windows: d:/test ；linux: /myself/resource</div>
                                <div class="e-msg">{{ val.error.path }}</div>
                            </td>
                        </tr>

                        <tr :class="{error: val.error.prefix}">
                            <td>路径前缀</td>
                            <td>
                                <input type="text" v-model="form.prefix" @input="val.error.prefix = ''" class="form-text">
                                <span class="need">*</span>
                                <div class="msg">例如：upload</div>
                                <div class="e-msg">{{ val.error.prefix }}</div>
                            </td>
                        </tr>

                        <tr :class="{error: val.error.os}">
                            <td>操作系统</td>
                            <td>
                                <i-radio-group v-model="form.os"  @input="val.error.os = ''">
                                    <i-radio v-for="(v,k) in TopContext.business.disk.os" :key="k" :label="k">{{ v }}</i-radio>
                                </i-radio-group>
                                <span class="need">*</span>
                                <div class="msg">默认：windows</div>
                                <div class="e-msg">{{ val.error.os }}</div>
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

                        <tr :class="{error: val.error.is_linked}" v-if="val.mode === 'edit'">
                            <td>已创建链接？</td>
                            <td>
                                <i-radio-group v-model="form.is_linked"  @input="val.error.is_linked = ''">
                                    <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                                </i-radio-group>
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.is_linked }}</div>
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
                <i-button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent">确认</i-button>
                <i-button v-ripple type="error" @click="closeFormModal">关闭</i-button>
            </template>
        </my-form-modal>
    </div>
</template>

<script src="./js/form.js"></script>

<style scoped>

</style>
