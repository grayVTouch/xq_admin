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
                        <tr :class="{error: val.error.value}">
                            <td>位置</td>
                            <td>
                                <input type="text" v-model="form.value" @input="val.error.value=''" class="form-text">
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.value }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.name}">
                            <td>名称</td>
                            <td>
                                <input type="text" v-model="form.name" @input="val.error.name=''" class="form-text">
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.name }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.platform}">
                            <td>所属平台</td>
                            <td>
                                <i-select v-model="form.platform" :style="`width: ${TopContext.style.inputItemW}px`">
                                    <i-option v-for="(v,k) in TopContext.business.platform" :key="k" :value="k">{{ v }}</i-option>
                                </i-select>
                                <span class="need">*</span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.platform }}</div>
                            </td>
                        </tr>
                        <tr :class="{error: val.error.description}">
                            <td>描述</td>
                            <td>
                                <textarea v-model="form.description" class="form-textarea" @input="val.error.description = ''"></textarea>
                                <span class="need"></span>
                                <div class="msg"></div>
                                <div class="e-msg">{{ val.error.description }}</div>
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
