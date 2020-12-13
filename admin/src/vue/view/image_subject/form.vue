<template>
    <div>
        <my-form-drawer
                v-model="val.show"
                :title="title"
                :loading="val.pending.submit"
                @on-ok="submitEvent"
                @on-cancel="closeFormModal"
        >

            <div class="run-action-title" slot="header">
                <div class="left">{{ title }}</div>
                <div class="right">
                    <i-button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</i-button>
                    <i-button v-ripple type="error" @click="closeFormModal"><my-icon icon="guanbi" />关闭</i-button>
                </div>
            </div>

            <form class="form subject-form" @submit.prevent="submitEvent" slot="default">
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
                            <my-select :width="TopContext.style.inputItemW" :data="modules" v-model="form.module_id" @change="val.error.module_id = ''"></my-select>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.module_id }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.thumb}">
                        <td>封面</td>
                        <td>
                            <div ref="thumb">
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

                    <tr :class="{error: val.error.attr}">
                        <td>属性</td>
                        <td>
                            <div class="attr">
                                <div class="line">
                                    <table class="line-table">
                                        <thead>
                                        <tr>
                                            <th>字段</th>
                                            <th>值</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(v,k) in attr" :key="k">
                                            <td><input type="text" v-model="v.field" class="form-text w-r-100"></td>
                                            <td><input type="text" v-model="v.value" class="form-text w-r-100"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="line m-t-10">
                                    <my-table-button @click="attr.push({field: '' , value: ''})"><my-icon icon="add" />添加</my-table-button>
                                    <my-table-button type="error" @click="attr.pop()"><my-icon icon="delete" />减少</my-table-button>
                                </div>
                            </div>
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.attr }}</div>
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
                            <div class="e-msg">{{ val.error.thumb }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <i-button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</i-button>
                            <button v-show="false" type="submit"></button>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </form>

        </my-form-drawer>

        <my-user-selector ref="user-selector" @on-change="userChangeEvent"></my-user-selector>
    </div>
</template>

<script src="./js/form.js"></script>

<style scoped>

</style>
