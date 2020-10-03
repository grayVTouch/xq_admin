<template>
    <my-form-drawer :title="title" v-model="val.drawer">

        <template slot="header">

            <div class="run-action-title">
                <div class="left">{{ title }}</div>
                <div class="right">
                    <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</Button>
                    <Button v-ripple type="error" @click="closeFormDrawer"><my-icon icon="guanbi" />关闭</Button>
                </div>
            </div>

        </template>

        <template slot="default">
            <form @submit.prevent="submitEvent" class="form">
                <div class="menu">
                    <div class="menu-item" v-ripple :class="{cur: val.tab === 'base'}" @click="val.tab = 'base'">基本信息</div>
                    <div class="menu-item" v-ripple :class="{cur: val.tab === 'image'}" @click="val.tab = 'image'">图片信息</div>
                </div>
                <div class="menu-mapping-content">
                    <div class="" v-show="val.tab === 'base'">
                        <table class="input-table">
                            <tbody>

                            <tr :class="{error: val.error.name}">
                                <td>名称：</td>
                                <td>
                                    <input type="text" class="form-text" v-model="form.name" @input="val.error.name = ''">
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.name }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.user_id}">
                                <td>所属用户：</td>
                                <td>
                                    <input type="text" readonly="readonly" :value="`${getUsername(users.current.username , users.current.nickname)}【${users.current.id}】`" class="form-text w-150 run-cursor-not-allow">
                                    如需重新搜索，请点击
                                    <Button @click="searchUserEvent">搜索</Button>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.user_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.module_id}">
                                <td>所属模块：</td>
                                <td>

                                    <my-select :data="modules" v-model="form.module_id" @change="moduleChangedEvent"></my-select>
                                    <my-loading v-if="val.pending.getModules"></my-loading>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.module_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.category_id}">
                                <td>所属分类：</td>
                                <td>
                                    <my-deep-select :data="categories" v-model="form.category_id" @on-change="val.error.category_id = ''" :has="false"></my-deep-select>
                                    <my-loading v-if="val.pending.getCategories"></my-loading>
                                    <span class="need">*</span>
                                    <div class="msg">请务必在选择模块后操作</div>
                                    <div class="e-msg">{{ val.error.category_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.type}">
                                <td>类型：</td>
                                <td>
                                    <RadioGroup v-model="form.type" @on-change="typeChangedEvent">
                                        <Radio v-for="(v,k) in $store.state.business.image_subject.type" :key="k" :label="k">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.type }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.subject_id}" v-show="form.type === 'pro'">
                                <td>关联主体：</td>
                                <td>
                                    <input type="text" readonly="readonly" :value="`${subjects.current.name}【${subjects.current.id}】`" class="form-text w-150 run-cursor-not-allow">
                                    如需重新搜索，请点击
                                    <Button @click="searchSubjectEvent">搜索</Button>
                                    <span class="need">*</span>
                                    <div class="msg">请务必在选择模块后操作</div>
                                    <div class="e-msg">{{ val.error.subject_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.tags}">
                                <td>标签：</td>
                                <td>
                                    <div class="tags">
                                        <div class="line top">

                                            <div class="active-tag" v-for="v in form.tags" @click="destroyTag(v.tag_id , false)">
                                                <div class="text"><my-loading size="18" color="#b1b6bd" v-if="val.pending['destroy_tag_' + v.tag_id]" />{{ v.name }}</div>
                                                <div class="close">
                                                    <div class="inner">
                                                        <div class="positive"></div>
                                                        <div class="negative"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="active-tag" v-for="v in tags" @click="destroyTag(v.id)">
                                                <div class="text">{{ v.name }}</div>
                                                <div class="close">
                                                    <div class="inner">
                                                        <div class="positive"></div>
                                                        <div class="negative"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tag-input" ref="tag-input-outer"><span contenteditable="true" ref="tag-input" class="input" @input="val.error.tags = ''" @keyup.enter="createOrAppendTag"></span></div>
                                        </div>
                                        <div class="line btm">
                                            <h5 class="title">推荐标签（选择模块后该列表会更新）</h5>
                                            <div class="__tags__">
                                                <span class="tag run-action-feedback" v-for="v in topTags" @click="appendTag(v)">{{ v.name }}</span>
                                                <!--                                                <span class="tag run-action-feedback">发放</span>-->
                                            </div>
                                        </div>
                                    </div>
                                    <span class="need"></span>
                                    <div class="msg">请选择模块后操作；输入框按回车键可搜寻已有标签，如不存在会自动创建</div>
                                    <div class="e-msg">{{ val.error.tags }}</div>
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

                            <tr :class="{error: val.error.weight}">
                                <td>权重</td>
                                <td>
                                    <input type="number" v-model="form.weight" @input="val.error.weight = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.thumb }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.view_count}">
                                <td>浏览次数</td>
                                <td>
                                    <input type="number" v-model="form.view_count" @input="val.error.view_count = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.view_count }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.praise_count}">
                                <td>获赞次数</td>
                                <td>
                                    <input type="number" v-model="form.praise_count" @input="val.error.praise_count = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.praise_count }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.status}">
                                <td>状态</td>
                                <td>
                                    <RadioGroup v-model="form.status">
                                        <Radio v-for="(v,k) in $store.state.business.image_subject.status" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：待审核</div>
                                    <div class="e-msg">{{ val.error.status }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.fail_reason}" v-show="form.status === -1">
                                <td>失败原因</td>
                                <td>
                                    <textarea v-model="form.fail_reason" class="form-textarea"></textarea>
                                    <span class="need">*</span>
                                    <div class="msg">当状态为审核失败的时候必填</div>
                                    <div class="e-msg">{{ val.error.fail_reason }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.description}">
                                <td>描述</td>
                                <td>
                                    <textarea v-model="form.description" class="form-textarea"></textarea>
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.description }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.create_time}">
                                <td>创建时间</td>
                                <td>
                                    <DatePicker type="datetime" v-model="createTime" format="yyyy-MM-dd HH:mm:ss" @on-change="setCreateTimeEvent" class="iview-form-input"></DatePicker>
                                    <span class="need"></span>
                                    <div class="msg">如不提供，则默认使用当前时间</div>
                                    <div class="e-msg">{{ val.error.create_time }}</div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <button class="hide" type="submit"><my-icon icon="tijiao" />提交</button>
                                    <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</Button>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="" v-show="val.tab === 'image'">
                        <div class="image-info">
                            <div class="line upload">
                                <div class="run-title">
                                    <div class="left">上传图片</div>
                                    <div class="right"></div>
                                </div>
                                <div>
                                    <table class="input-table">
                                        <tbody>
                                        <tr :class="{error: val.error.images}">
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
                                            <my-table-button type="error" :loading="val.pending['destroyAll']" v-if="val.selectedIds.length > 0" @click="destroyAllEvent">删除选中项 （{{ val.selectedIds.length }}）</my-table-button>
                                        </div>
                                    </div>
                                    <div>
                                        <Table border :columns="table.field" :data="table.data" @on-selection-change="selectedEvent" style="width: 100%;">
                                            <template v-slot:path="{row,index}">
                                                <img :src="row.src ? row.src : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image" @click.stop="link(row.__path__)">
                                            </template>
                                            <template v-slot:action="{row,index}">
                                                <my-table-button :loading="val.pending['delete_' + row.id]" @click="destroyEvent(index , row)">删除</my-table-button>
                                            </template>
                                        </Table>
                                    </div>
                                </div>

                                <div class="line">
                                    <my-table-button type="error" :loading="val.pending['destroyAll']" v-if="val.selectedIds.length > 0" @click="destroyAllEvent">删除选中项 （{{ val.selectedIds.length }}）</my-table-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- 请选择用户 -->
            <my-form-modal v-model="val.modalForUser" title="请选择用户" :width="1000">
                <template slot="footer">
                    <Button v-ripple type="error" @click="val.modalForUser=false">取消</Button>
                </template>
                <template slot="default">
                    <div class="search-modal">
                        <div class="input">
                            <div class="input-mask"><input type="text" v-model="users.value" @keyup.enter="searchUser" placeholder="请输入搜索值"></div>
                            <div class="msg">输入id、用户名、手机号码、邮箱可查询</div>
                        </div>
                        <div class="list">
                            <Table border :loading="val.pending.searchUser" :data="users.data" :columns="users.field" @on-row-click="updateUserEvent">
                                <template v-slot:avatar="{row,index}"><img :src="row.avatar ? row.avatar : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                                <template v-slot:action="{row,index}"><my-table-button>选择</my-table-button></template>
                            </Table>
                        </div>
                        <div class="pager">
                            <my-page :total="users.total" :limit="users.limit" :page="users.page" @on-change="userPageEvent"></my-page>
                        </div>
                    </div>
                </template>
            </my-form-modal>

            <!-- 选择关联主体 -->
            <my-form-modal v-model="val.modalForSubject" title="请选择关联主体" :width="1000">
                <template slot="footer">
                    <Button v-ripple type="error" @click="val.modalForSubject=false">取消</Button>
                </template>
                <template slot="default">

                    <div class="search-modal">
                        <div class="input">
                            <div class="input-mask"><input type="text" v-model="subjects.value" @keyup.enter="searchSubject" placeholder="请输入搜索值"></div>
                            <div class="msg">输入id、名称可查询</div>
                        </div>
                        <div class="list">
                            <Table border  :loading="val.pending.searchSubject" :data="subjects.data" :columns="subjects.field" @on-row-click="updateSubjectEvent">
                                <template v-slot:thumb="{row,index}"><img :src="row.thumb ? row.thumb : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                                <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                                <template v-slot:action="{row,index}"><my-table-button>选择</my-table-button></template>
                            </Table>
                        </div>
                        <div class="pager">
                            <my-page :total="subjects.total" :limit="subjects.limit" :page="subjects.page" @on-change="subjectPageEvent"></my-page>
                        </div>
                    </div>
                </template>
            </my-form-modal>

        </template>
    </my-form-drawer>
</template>

<script src="./js/form.js"></script>
<style scoped src="./css/form.css"></style>
