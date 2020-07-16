<template>
    <user-base>
        <template slot="title">我的信息 <my-loading v-if="val.pending.user" size="16"></my-loading></template>
        <template slot="action"></template>
        <form @submit.prevent="submit" slot="content">
            <table class="input-table">
                <tbody>
                <tr :class="{error: val.error.username}">
                    <td>用户名</td>
                    <td>
                        <input type="text" class="form-text run-cursor-not-allow" @input="val.error.username = ''" v-model="form.username" readonly>
                        <span class="need"></span>
                        <div class="msg">禁止修改</div>
                        <div class="e-msg">{{ val.error.username }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.nickname}">
                    <td>昵称</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.nickname" @input="val.error.nickname = ''">
                        <span class="need"></span>
                        <div class="msg"></div>
                        <div class="e-msg">{{ val.error.nickname }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.avatar}">
                    <td>头像</td>
                    <td>
                        <div class="uploader-mask" ref="uploader-mask">

                            <div class='uploader'>
                                <div class="upload">
                                    <div class="handler">

                                        <div class="line input hide">
                                            <input type="file" class="file-input" @change="val.error.avatar = ''">
                                        </div>

                                        <div class="line icon">
                                            <div class="ico">
                                                <div class="feedback run-action-feedback-round"><i class="iconfont run-iconfont run-iconfont-shangchuan run-white"></i></div>
                                                <div class="clear run-action-feedback-round" title="清空"><i class="iconfont run-iconfont run-iconfont-qingkong"></i></div>
                                            </div>
                                            <div class="text">请选择要上传的文件</div>
                                        </div>

                                    </div>

                                    <div class="msg">提示内容</div>
                                </div>
                                <div class="preview"></div>

                            </div>

                        </div>
                        <div class="msg"></div>
                        <div class="e-msg">{{ val.error.avatar }}</div>


                    </td>
                </tr>

                <tr :class="{error: val.error.sex}">
                    <td>性别</td>
                    <td>
                        <label class="m-r-10" v-for="(v,k) in $store.state.context.business.user.sex" :key="k"><input type="radio" name="sex" :value="k" @change="val.error.sex = ''" v-model="form.sex"> {{ v }}</label>
                        <span class="need">*</span>
                        <div class="msg"></div>
                        <div class="e-msg">{{ val.error.sex }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.phone}">
                    <td>手机号码</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.phone" @input="val.error.phone = ''">
                        <span class="need"></span>
                        <div class="msg">请正确输入 11 位手机号码</div>
                        <div class="e-msg">{{ val.error.phone }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.email}">
                    <td>邮箱</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.email" @input="val.error.email = ''">
                        <span class="need"></span>
                        <div class="msg">例如：test@126.com</div>
                        <div class="e-msg">{{ val.error.email }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.birthday}">
                    <td>生日</td>
                    <td>
                        <input type="text" class="form-text" v-model="form.birthday" @input="val.error.birthday = ''">
                        <span class="need"></span>
                        <div class="msg">格式：YYYY-MM-DD HH:II:SS</div>
                        <div class="e-msg">{{ val.error.birthday }}</div>
                    </td>
                </tr>

                <tr :class="{error: val.error.description}">
                    <td>个人简介</td>
                    <td>
                        <textarea v-model="form.description" @input="val.error.description = ''" class="form-textarea"></textarea>
                        <span class="need"></span>
                        <div class="msg"></div>
                        <div class="e-msg">{{ val.error.description }}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <button type="submit" class="button submit-button" v-ripple>
                            <my-loading size="16" class="m-r-5" v-if="val.pending.submit"></my-loading>提交
                        </button>
                    </td>
                </tr>

                </tbody>
            </table>
        </form>
    </user-base>
</template>

<script src="./js/info.js"></script>
<style src="./css/input.css"></style>
<style scoped src="./css/info.css"></style>