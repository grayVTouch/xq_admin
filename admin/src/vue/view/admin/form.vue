<template>
    <my-form-modal
            v-model="val.show" :title="title" :loading="val.pending.submit" @on-ok="submitEvent" @on-cancel="closeFormModal">
        <template slot="default">
            <form class="form" @submit.prevent="submitEvent">
                <table class="input-table">
                    <tbody>

                    <tr :class="{error: val.error.username}">
                        <td>名称</td>
                        <td>
                            <input type="text" v-model="form.username" @input="val.error.username=''" class="form-text">
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.username }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.password}">
                        <td>密码</td>
                        <td>
                            <input type="text" v-model="form.password" @input="val.error.password=''" class="form-text">
                            <span class="need"></span>
                            <div class="msg">为空，则使用原密码</div>
                            <div class="e-msg">{{ val.error.password }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.role_id}">
                        <td>角色</td>
                        <td>
                            <my-select :data="roles" v-model="form.role_id"></my-select>
                            <my-loading v-if="val.pending.getRoles"></my-loading>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.role_id }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.avatar}">
                        <td>头像</td>
                        <td>
                            <div ref="avatar">
                                <!-- 上传图片组件 -->
                                <div class='uploader'>
                                    <div class="upload">
                                        <div class="handler">

                                            <div class="line input hide">
                                                <input type="file" class="file-input">
                                            </div>

                                            <div class="line icon">
                                                <div class="ico">
                                                    <div class="feedback run-action-feedback-round"><i class="iconfont run-iconfont run-iconfont-shangchuan"></i></div>
                                                    <div class="clear run-action-feedback-round" title="清空"><i class="iconfont run-iconfont run-iconfont-qingkong"></i></div>
                                                </div>
                                                <div class="text">请选择要上传的文件</div>
                                            </div>

                                        </div>

                                        <div class="msg"></div>
                                    </div>
                                    <div class="preview"></div>

                                </div>
                            </div>

                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.thumb }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.sex}">
                        <td>性别</td>
                        <td>
                            <RadioGroup v-model="form.sex">
                                <Radio v-for="(v,k) in $store.state.business.user.sex" :key="k" :label="k">{{ v }}</Radio>
                            </RadioGroup>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.sex }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.birthday}">
                        <td>生日</td>
                        <td>
                            <i-date-picker v-model="val.birthday" format="yyyy-MM-dd" class="iview-form-input" @on-change="setDate"></i-date-picker>
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.birthday }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.phone}">
                        <td>手机号码</td>
                        <td>
                            <input type="text" v-model="form.phone" @input="val.error.phone=''" class="form-text">
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.phone }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.email}">
                        <td>电子邮件</td>
                        <td>
                            <input type="text" v-model="form.email" @input="val.error.email=''" class="form-text">
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.email }}</div>
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
            <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent">确认</Button>
            <Button v-ripple type="error" @click="closeFormModal">关闭</Button>
        </template>
    </my-form-modal>
</template>

<script src="./js/form.js"></script>

<style scoped>

</style>
