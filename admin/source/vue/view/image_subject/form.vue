<template>
    <my-form-drawer :title="title" v-model="drawer">

        <template slot="header">

            <div class="run-action-title">
                <div class="left">{{ title }}</div>
                <div class="right">
                    <Button type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</Button>
                    <Button type="error" @click="closeFormDrawer"><my-icon icon="guanbi" />关闭</Button>
                </div>
            </div>

        </template>

        <template slot="default">
            <form @submit.prevent="submitEvent" class="form">
                <Tabs v-model="val.tab">
                    <TabPane label="基本信息" name="base">
                        <table class="input-table">
                            <tbody>

                            <tr :class="getClass(val.error.name)" id="form-name">
                                <td>专题名称：</td>
                                <td>
                                    <input type="text" class="form-text" v-model="form.name" @input="val.error.name = ''">
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.name }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.user_id)" id="form-user_id">
                                <td>所属用户：</td>
                                <td>
                                    <i-select
                                            class="iview-form-select"
                                            v-model="form.user_id"
                                            :filterable="true"
                                            :remote="true"
                                            :remote-method="searchUser"
                                            :loading="val.pending.searchUser"
                                            @on-change="val.error.user_id = ''"
                                            placeholder="请搜索"
                                    >
                                        <i-option v-for="v in users" :value="v.id" :key="v.id">{{ v.username }}</i-option>
                                    </i-select>
                                    <span class="need">*</span>
                                    <div class="msg">输入用户id、用户名、手机号码、邮箱可查询用户</div>
                                    <div class="e-msg">{{ val.error.user_id }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.module_id)" id="form-module_id">
                                <td>所属模块：</td>
                                <td>
                                    <my-select :data="modules" v-model="form.module_id" @on-change="val.error.module_id = ''" :has="false"></my-select>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.module_id }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.category_id)" id="form-category_id">
                                <td>所属分类：</td>
                                <td>
                                    <my-select :data="categories" v-model="form.category_id" @on-change="val.error.category_id = ''" :has="false"></my-select>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.category_id }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.type)" id="form-type">
                                <td>类型：</td>
                                <td>
                                    <RadioGroup v-model="form.type">
                                        <Radio v-for="(v,k) in $store.state.business.image_subject.type" :key="k" :label="k">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.type }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.subject_id)" id="form-subject_id" v-show="form.type === 'pro'">
                                <td>关联主体：</td>
                                <td>
                                    <i-select
                                            class="iview-form-select"
                                            v-model="form.subject_id"
                                            :filterable="true"
                                            :remote="true"
                                            placeholder="请搜索"
                                            :remote-method="searchSubject"
                                            :loading="val.pending.searchSubject"
                                            @on-change="val.error.subject_id = ''"
                                    >
                                        <i-option v-for="v in subjects" :value="v.id" :key="v.id">{{ v.name }}</i-option>
                                    </i-select>
                                    <span class="need">*</span>
                                    <div class="msg">输入关联主体id、名称可查询</div>
                                    <div class="e-msg">{{ val.error.subject_id }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.tag)" id="form-tag">
                                <td>标签：</td>
                                <td>
                                    <div class="tags">
                                        <div class="line top">

                                            <div class="active-tag" v-for="v in form.__tag__" @click="destroyTag(v.name)">
                                                <div class="text">{{ v.name }}</div>
                                                <div class="close">
                                                    <div class="inner">
                                                        <div class="positive"></div>
                                                        <div class="negative"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tag-input" ref="tag-input-outer"><span contenteditable="true" ref="tag-input" class="input" @keyup.enter="createOrAppendTag"></span></div>
                                        </div>
                                        <div class="line btm">
                                            <h5 class="title">推荐标签</h5>
                                            <div class="__tags__">
                                                <span class="tag run-action-feedback" v-for="v in topTags" @click="appendTag(v.name)">{{ v.name }}</span>
<!--                                                <span class="tag run-action-feedback">发放</span>-->
                                            </div>
                                        </div>
                                    </div>
                                    <span class="need"></span>
                                    <div class="msg">输入框按回车键可搜寻已有标签，如不存在会自动创建</div>
                                    <div class="e-msg">{{ val.error.tag }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.thumb)" id="form-thumb">
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

                            <tr :class="getClass(val.error.weight)" id="form-weight">
                                <td>权重</td>
                                <td>
                                    <input type="number" v-model="form.weight" @input="val.error.weight = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.thumb }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.view_count)" id="form-view_count">
                                <td>浏览次数</td>
                                <td>
                                    <input type="number" v-model="form.view_count" @input="val.error.view_count = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.view_count }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.praise_count)" id="form-praise_count">
                                <td>获赞次数</td>
                                <td>
                                    <input type="number" v-model="form.praise_count" @input="val.error.praise_count = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.praise_count }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.status)" id="form-status">
                                <td>状态</td>
                                <td>
                                    <RadioGroup v-model="form.status">
                                        <Radio v-for="(v,k) in $store.state.business.image_subject.status" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.status }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.fail_reason)" v-show="form.status === -1" id="form-fail_reason">
                                <td>失败原因</td>
                                <td>
                                    <textarea v-model="form.fail_reason" class="form-textarea"></textarea>
                                    <span class="need">*</span>
                                    <div class="msg">当状态为审核失败的时候必填</div>
                                    <div class="e-msg">{{ val.error.fail_reason }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.description)" id="form-description">
                                <td>描述</td>
                                <td>
                                    <textarea v-model="form.description" class="form-textarea"></textarea>
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.description }}</div>
                                </td>
                            </tr>

                            <tr :class="getClass(val.error.create_time)" id="form-create_time">
                                <td>创建时间</td>
                                <td>
                                    <DatePicker type="datetime" v-model="createTime" format="yyyy-MM-dd HH:mm:ss" @on-change="setDatetimeEvent" class="iview-form-input"></DatePicker>
                                    <span class="need"></span>
                                    <div class="msg">如不提供，则默认使用当前时间</div>
                                    <div class="e-msg">{{ val.error.create_time }}</div>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </TabPane>
                    <TabPane label="图片信息" name="image">
                        <div class="image-info">
                            <div class="line upload">
                                <div class="run-title">
                                    <div class="left">上传图片</div>
                                    <div class="right"></div>
                                </div>
                                <div>
                                    <table class="input-table">
                                        <tbody>
                                        <tr :class="getClass(val.error.images)" id="form-images">
                                            <td>
                                                <div ref="images">
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
                                                <div class="e-msg">{{ val.error.images }}</div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="images">
                                <div class="line">
                                    <div class="run-title">
                                        <div class="left">图片列表</div>
                                        <div class="right">
                                            <my-table-button type="error" :loading="val.pending['destroyAll']" @click="destroyAllEvent">删除选中项</my-table-button>
                                        </div>
                                    </div>
                                    <div>
                                        <Table border :columns="table.field" :data="table.data" @on-selection-change="selectedEvent" style="width: 100%;">
                                            <template v-slot:path="{row,index}">
                                                <img :src="row.path ? row.__path__ : $store.state.context.res.notFound" height="40" class="image" @click.stop="link(row.__path__)">
                                            </template>
                                            <template v-slot:action="{row,index}">
                                                <my-table-button :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)">删除</my-table-button>
                                            </template>
                                        </Table>
                                    </div>
                                </div>

                                <div class="line">
                                    <my-table-button type="error" :loading="val.pending['destroyAll']" @click="destroyAllEvent">删除选中项</my-table-button>
                                </div>
                            </div>
                        </div>
                    </TabPane>
                </Tabs>
            </form>

        </template>
    </my-form-drawer>
</template>

<script src="./js/form.js"></script>
<style scoped src="./css/form.css"></style>