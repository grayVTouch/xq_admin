<template>
    <user-base>
        <template slot="title">修改密码</template>
        <form @submit.prevent="submitEvent" slot="content">
            <table class="input-table">
                <tbody>
                <tr :class="{error: val.error.old_password}">
                    <td>原密码</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.old_password" @input="val.error.old_password = ''">
                        <span class="need"></span>
                        <div class="msg">请输入至少6位长度密码</div>
                        <div class="e-msg">{{ val.error.old_password }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.password}">
                    <td>原密码</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.password" @input="val.error.password = ''">
                        <span class="need"></span>
                        <div class="msg">请输入至少6位长度密码</div>
                        <div class="e-msg">{{ val.error.password }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.confirm_password}">
                    <td>原密码</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.confirm_password" @input="val.error.confirm_password = ''">
                        <span class="need"></span>
                        <div class="msg">请输入至少6位长度密码</div>
                        <div class="e-msg">{{ val.error.confirm_password }}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <button type="submit" class="button submit-button" v-ripple>
                            <my-loading size="16" class="m-r-5" v-if="val.pending.submitEvent"></my-loading>提交
                        </button>
                    </td>
                </tr>

                </tbody>
            </table>
        </form>
    </user-base>
</template>

<script src="./js/password.js"></script>
<style src="./css/input.css"></style>
<style scoped src="./css/password.css"></style>
