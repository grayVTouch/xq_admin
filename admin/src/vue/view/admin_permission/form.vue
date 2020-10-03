<template>
    <my-form-drawer :title="title" v-model="val.drawer">

        <template slot="header">
            <div class="run-action-title">
                <div class="left">{{ title }}</div>
                <div class="right">
                    <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent">提交</Button>
                    <Button v-ripple type="error" @click="closeFormDrawer">关闭</Button>
                </div>
            </div>
        </template>

        <template slot="default">
            <form class="form" @submit.prevent="submitEvent" ref="form">
                <table class="input-table">
                    <tbody>
                    <tr :class="{error: val.error.cn}">
                        <td>中文名称</td>
                        <td>
                            <input type="text" v-model="form.cn"  @input="val.error.cn = ''" class="form-text">
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.cn }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: val.error.en}">
                        <td>英文名称</td>
                        <td>
                            <input type="text" v-model="form.en"  @input="val.error.en = ''" class="form-text">
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.en }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: val.error.value}">
                        <td>权限</td>
                        <td>
                            <input type="text" class="form-text"  @input="val.error.value = ''" v-model="form.value">
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.value }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: val.error.p_id}">
                        <td>上级权限</td>
                        <td>
                            <my-deep-select :data="permissions" v-model="form.p_id" :has="true" :attr="val.attr"  @change="val.error.p_id = ''"></my-deep-select>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.p_id }}</div>
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
                    <tr :class="{error: val.error.type}">
                        <td>类型</td>
                        <td>
                            <RadioGroup v-model="form.type" @on-change="val.error.type = ''">
                                <Radio v-for="(v,k) in $store.state.business.admin_permission.type" :key="k" :label="k">{{ v }}</Radio>
                            </RadioGroup>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.type }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: val.error.is_menu}">
                        <td>菜单？</td>
                        <td>
                            <RadioGroup v-model="form.is_menu"  @on-change="val.error.is_menu = ''">
                                <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                            </RadioGroup>
                            <span class="need">*</span>
                            <div class="msg">默认：否</div>
                            <div class="e-msg">{{ val.error.is_menu }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: val.error.is_view}">
                        <td>视图？</td>
                        <td>
                            <RadioGroup v-model="form.is_view"  @on-change="val.error.is_view = ''">
                                <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                            </RadioGroup>
                            <span class="need">*</span>
                            <div class="msg">默认：是</div>
                            <div class="e-msg">{{ val.error.is_view }}</div>
                        </td>
                    </tr>
                    <tr :class="{error: val.error.enable}">
                        <td>启用？</td>
                        <td>
                            <RadioGroup v-model="form.enable"  @input="val.error.enable = ''">
                                <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                            </RadioGroup>
                            <span class="need">*</span>
                            <div class="msg">默认：开启</div>
                            <div class="e-msg">{{ val.error.enable }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.b_ico}" id="b_ico">
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
                            <div class="e-msg">{{ val.error.b_ico }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.s_ico}" id="s_ico">
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
                            <div class="e-msg">{{ val.error.b_ico }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.weight}">
                        <td>权重</td>
                        <td>
                            <input type="number" class="form-text"  @input="val.error.weight = ''" v-model="form.weight">
                            <span class="need"></span>
                            <div class="msg">请提供整数</div>
                            <div class="e-msg">{{ val.error.weight }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent">提交</Button>
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