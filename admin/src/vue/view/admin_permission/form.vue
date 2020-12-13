<template>
    <my-form-drawer :title="title" v-model="myValue.drawer">

        <template slot="header">
            <div class="run-action-title">
                <div class="left">{{ title }}</div>
                <div class="right">
                    <i-button v-ripple type="primary" :loading="myValue.pending.submitEvent" @click="submitEvent">提交</i-button>
                    <i-button v-ripple type="error" @click="closeFormModal">关闭</i-button>
                </div>
            </div>
        </template>

        <template slot="default">
            <form class="form" @submit.prevent="submitEvent" ref="form">
                <table class="input-table">
                    <tbody>
                    <tr :class="{error: myValue.error.cn}">
                        <td>中文名称</td>
                        <td>
                            <input type="text" v-model="form.cn"  @input="myValue.error.cn = ''" class="form-text">
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.cn }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.en}">
                        <td>英文名称</td>
                        <td>
                            <input type="text" v-model="form.en"  @input="myValue.error.en = ''" class="form-text">
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.en }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.value}">
                        <td>权限</td>
                        <td>
                            <input type="text" class="form-text"  @input="myValue.error.value = ''" v-model="form.value">
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.value }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.p_id}">
                        <td>上级权限</td>
                        <td>
                            <my-deep-select :data="permissions" v-model="form.p_id" :has="true" :attr="myValue.attr"  @change="myValue.error.p_id = ''"></my-deep-select>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.p_id }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.description}">
                        <td>描述</td>
                        <td>
                            <textarea v-model="form.description" class="form-textarea" @input="myValue.error.description = ''"></textarea>
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.description }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.type}">
                        <td>类型</td>
                        <td>
                            <i-radio-group v-model="form.type" @on-change="myValue.error.type = ''">
                                <i-radio v-for="(v,k) in TopContext.business.admin_permission.type" :key="k" :label="k">{{ v }}</i-radio>
                            </i-radio-group>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.type }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.is_menu}">
                        <td>菜单？</td>
                        <td>
                            <i-radio-group v-model="form.is_menu"  @on-change="myValue.error.is_menu = ''">
                                <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                            </i-radio-group>
                            <span class="need">*</span>
                            <div class="msg">默认：否</div>
                            <div class="e-msg">{{ myValue.error.is_menu }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: myValue.error.is_view}">
                        <td>视图？</td>
                        <td>
                            <i-radio-group v-model="form.is_view"  @on-change="myValue.error.is_view = ''">
                                <i-radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</i-radio>
                            </i-radio-group>
                            <span class="need">*</span>
                            <div class="msg">默认：是</div>
                            <div class="e-msg">{{ myValue.error.is_view }}</div>
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

                    <tr :class="{error: myValue.error.b_ico}" id="b_ico">
                        <td>大图标</td>
                        <td>
                            <div ref="b-ico">
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

                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.b_ico }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: myValue.error.s_ico}" id="s_ico">
                        <td>小图标</td>
                        <td>
                            <div ref="s-ico">
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
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ myValue.error.b_ico }}</div>
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

                    <tr>
                        <td colspan="2">
                            <i-button v-ripple type="primary" :loading="myValue.pending.submitEvent" @click="submitEvent">提交</i-button>
                            <button v-show="false" type="submit"></button>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </form>
        </template>

    </my-form-drawer>
</template>

<script src="./js/form.js"></script>

<style scoped>
    .my-drawer-form-wrapper {

    }

    .my-drawer-form-wrapper > .form {
        margin-bottom: 50px;
    }

    .my-drawer-form-wrapper > .footer {
        height: 50px;
        flex-shrink: 1;
    }


</style>
