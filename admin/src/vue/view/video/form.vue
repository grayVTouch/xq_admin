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
                    <div class="menu-item" v-ripple :class="{cur: val.tab === 'image'}" @click="val.tab = 'image'">视频信息</div>
                </div>
                <div class="menu-mapping-content">
                    <div class="" v-show="val.tab === 'base'">
                        <table class="input-table">
                            <tbody>

                            <tr :class="{error: val.error.name}">
                                <td>名称：</td>
                                <td>
                                    <input type="text" class="form-text" v-model="form.name" @input="val.error.name = ''">
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.name }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.type}">
                                <td>类型：</td>
                                <td>
                                    <RadioGroup v-model="form.type" @on-change="typeChangedEvent">
                                        <Radio v-for="(v,k) in $store.state.business.video.type" :key="k" :label="k">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：杂类</div>
                                    <div class="e-msg">{{ val.error.type }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.user_id}">
                                <td>所属用户：</td>
                                <td>
                                    <input type="text" readonly="readonly" :value="`${getUsername(users.current.username , users.current.nickname)}【${users.current.id}】`" class="form-text w-180 run-cursor-not-allow">
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

                            <tr :class="{error: val.error.category_id}" v-show="form.type === 'misc'">
                                <td>所属分类：</td>
                                <td>
                                    <my-deep-select :data="categories" v-model="form.category_id" @change="val.error.category_id = ''" :has="false"></my-deep-select>
                                    <span class="need">*</span>
                                    <my-loading v-if="val.pending.getCategories"></my-loading>
                                    <div class="msg">请务必在选择模块后操作</div>
                                    <div class="e-msg">{{ val.error.category_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.video_project_id}" v-show="form.type === 'pro'">
                                <td>视频专题：</td>
                                <td>
                                    <input type="text" readonly="readonly" :value="`${videoProjects.current.name}【${videoProjects.current.id}】`" class="form-text w-180 run-cursor-not-allow">
                                    如需重新搜索，请点击
                                    <Button @click="searchVideoProjectEvent">搜索</Button>
                                    <span class="need">*</span>
                                    <div class="msg">请务必在选择模块后操作；输入关联主体id、名称可查询</div>
                                    <div class="e-msg">{{ val.error.video_project_id }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.index}">
                                <td>视频索引</td>
                                <td>
                                    <input type="number" v-model="form.index" @input="val.error.index = ''" class="form-text">
                                    <span class="need">*</span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.index }}</div>
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

                            <tr :class="{error: val.error.play_count}">
                                <td>获赞次数</td>
                                <td>
                                    <input type="number" v-model="form.play_count" @input="val.error.play_count = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.play_count }}</div>
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

                            <tr :class="{error: val.error.against_count}">
                                <td>反对次数</td>
                                <td>
                                    <input type="number" v-model="form.against_count" @input="val.error.against_count = ''" class="form-text">
                                    <span class="need"></span>
                                    <div class="msg">仅允许整数</div>
                                    <div class="e-msg">{{ val.error.against_count }}</div>
                                </td>
                            </tr>

                            <tr :class="{error: val.error.status}">
                                <td>状态</td>
                                <td>
                                    <RadioGroup v-model="form.status">
                                        <Radio v-for="(v,k) in $store.state.business.video.status" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                    </RadioGroup>
                                    <span class="need">*</span>
                                    <div class="msg">默认：审核中</div>
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

                            <tr :class="{error: val.error.description}">
                                <td>描述</td>
                                <td>
                                    <textarea v-model="form.description" class="form-textarea"></textarea>
                                    <span class="need"></span>
                                    <div class="msg"></div>
                                    <div class="e-msg">{{ val.error.description }}</div>
                                </td>
                            </tr>

<!--                            <tr :class="{error: val.error.created_at}">-->
<!--                                <td>创建时间</td>-->
<!--                                <td>-->
<!--                                    <DatePicker type="datetime" v-model="createTime" format="yyyy-MM-dd HH:mm:ss" @on-change="setDatetimeEvent" class="iview-form-input"></DatePicker>-->
<!--                                    <span class="need"></span>-->
<!--                                    <div class="msg">如不提供，则默认使用当前时间</div>-->
<!--                                    <div class="e-msg">{{ val.error.created_at }}</div>-->
<!--                                </td>-->
<!--                            </tr>-->

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
                        <div class="video-info">
                            <div class="line upload-video">
                                <div class="run-title">
                                    <div class="left">上传视频</div>
                                    <div class="right"></div>
                                </div>
                                <div>
                                    <table class="input-table">
                                        <tbody>
                                        <tr :class="{error: val.error.src}">
                                            <td>
                                                <div ref="video">
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
                                                <div class="msg">
                                                    支持的视频格式有：mov,mp4,mkv,rm,rmvb,avi,flv
                                                    <template v-if="mode === 'edit'"><br><b>当视频处理状态不是 处理完成 或 处理失败 的时候禁止更改</b></template>
                                                </div>
                                                <div class="e-msg">{{ val.error.src }}</div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="line upload-subtitle">
                                <div class="run-title">
                                    <div class="left">上传字幕</div>
                                    <div class="right"></div>
                                </div>
                                <div>
                                    <table class="input-table">
                                        <tbody>
                                        <tr>
                                            <td>合并字幕？</td>
                                            <td>
                                                <RadioGroup v-model="form.merge_video_subtitle">
                                                    <Radio v-for="(v,k) in $store.state.context.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                                </RadioGroup>
                                                <span class="need">*</span>
                                                <div class="msg">默认：否；<br>当选择合并字幕的时候服务端会将字幕列表中的首个字幕合并到视频并删除其他字幕，合并完成也会删除合并字幕仅保留合并后的视频文件</div>
                                                <div class="e-msg">{{ form.merge_video_subtitle }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>字幕</td>
                                            <td>
                                                <div class="subtitles">
                                                    <div class="item" v-for="(v,k) in uVideoSubtitles">
                                                        <div class="name"><input type="text" placeholder="字幕名称" class="form-text" v-model="v.name"></div>
                                                        <div class="src"><input type="file" class="form-file" @change="videoSubtitleChangeEvent($event , v)"></div>

                                                        <div class="actions">
                                                            <my-table-button @click="uVideoSubtitles.splice(k,1)">删除</my-table-button>
                                                        </div>
                                                        <div class="loading" v-if="v.uploading"><my-loading v-if="v.uploading"></my-loading></div>
                                                        <div class="flag" v-if="v.uploaded"><my-icon icon="604xinxi_chenggong" class="run-green"></my-icon></div>
                                                        <div class="e-msg run-red">{{ v.error }}</div>
                                                    </div>
                                                </div>

                                                <div class="action">
                                                    <Button v-ripple @click="addVideoSubtitleEvent">新增</Button>
<!--                                                    <Button v-ripple :loading="val.pending.uploadVideoSubtitle" :disabled="canUploadVideoSubtitle" @click="uploadVideoSubtitleEvent">上传</Button>-->
                                                </div>

<!--                                                <div class="msg">在提交修改之前，请务必点击上传文件先将字幕文件保存到服务器！</div>-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="line files">
                                <div class="line">
                                    <div class="run-title">
                                        <div class="left">视频列表</div>
                                        <div class="right">
                                            <my-table-button type="error" :loading="val.pending['destroyVideos']" v-if="val.selectedIdsForVideo.length > 0" @click="destroyVideosEvent">删除选中项 （{{ val.selectedIdsForVideo.length }}）</my-table-button>
                                        </div>
                                    </div>
                                    <div>
                                        <Table style="width: 100%;" border :columns="videos.field" :data="videos.data" @on-selection-change="selectedVideoEvent">
                                            <template v-slot:src="{row,index}">
                                                <video muted="muted" controls :src="row.src ? row.src : $store.state.context.res.notFound" :height="$store.state.context.table.videoH"></video>
                                            </template>
                                            <template v-slot:action="{row,index}">
                                                <my-table-button :loading="val.pending['delete_' + row.id]" @click="destroyVideoEvent(index , row)">删除</my-table-button>
                                                <my-table-button @click="link(row.src , '_blank')">预览</my-table-button>
                                            </template>
                                        </Table>
                                    </div>
                                </div>

                                <div class="line">
                                    <my-table-button type="error" :loading="val.pending['destroyVideos']" v-if="val.selectedIdsForVideo.length > 0" @click="destroyVideosEvent">删除选中项 （{{ val.selectedIdsForVideo.length }}）</my-table-button>
                                </div>
                            </div>

                            <div class="line files">
                                <div class="line">
                                    <div class="run-title">
                                        <div class="left">字幕列表</div>
                                        <div class="right">
                                            <my-table-button type="error" :loading="val.pending['destroyVideoSubtitles']" v-if="val.selectedIdsForSubtitle.length > 0" @click="destroyVideoSubtitlesEvent">删除选中项 （{{ val.selectedIdsForSubtitle.length }}）</my-table-button>
                                        </div>
                                    </div>
                                    <div>
                                        <Table style="width: 100%;" border :columns="videoSubtitles.field" :data="videoSubtitles.data" @on-selection-change="selectedSubtitleEvent">
                                            <template v-slot:src="{row,index}">{{ row.src }}</template>
                                            <template v-slot:action="{row,index}">
                                                <my-table-button :loading="val.pending['delete_' + row.id]" @click="destroyVideoSubtitleEvent(index , row)">删除</my-table-button>
                                            </template>
                                        </Table>
                                    </div>
                                </div>

                                <div class="line">
                                    <my-table-button type="error" :loading="val.pending['destroyVideoSubtitles']" v-if="val.selectedIdsForSubtitle.length > 0" @click="destroyVideoSubtitlesEvent">删除选中项 （{{ val.selectedIdsForSubtitle.length }}）</my-table-button>
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
                            <div class="msg">输入用户id、用户名、手机号码、邮箱可查询用户</div>
                        </div>
                        <div class="list">
                            <Table border :loading="val.pending.searchUser" :data="users.data" :columns="users.field" @on-row-click="updateUserEvent">
                                <template v-slot:avatar="{row,index}"><img :src="row.avatar ? row.avatar : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                                <template v-slot:action="{row,index}">
                                    <my-table-button>选择</my-table-button>
                                </template>
                            </Table>
                        </div>
                        <div class="pager">
                            <my-page :total="users.total" :limit="users.limit" :page="users.page" @on-change="userPageEvent"></my-page>
                        </div>
                    </div>
                </template>
            </my-form-modal>

            <!-- 视频专题 -->
            <my-form-modal v-model="val.modalForVideoProject" title="请选择视频专题" :width="1000">
                <template slot="footer">
                    <Button v-ripple type="error" @click="val.modalForVideoProject=false">取消</Button>
                </template>
                <template slot="default">

                    <div class="search-modal">
                        <div class="input">
                            <div class="input-mask"><input type="text" v-model="videoProjects.value" @keyup.enter="searchVideoProject" placeholder="请输入搜索值"></div>
                            <div class="msg"></div>
                        </div>
                        <div class="list">
                            <Table border  :loading="val.pending.searchVideoProject" :data="videoProjects.data" :columns="videoProjects.field" @on-row-click="updateVideoProjectEvent">
                                <template v-slot:thumb="{row,index}"><img :src="row.thumb ? row.thumb : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                                <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                                <template v-slot:action="{row,index}">
                                    <my-table-button>选择</my-table-button>
                                </template>
                            </Table>
                        </div>
                        <div class="pager">
                            <my-page :total="videoProjects.total" :limit="videoProjects.limit" :page="videoProjects.page" @on-change="videoProjectPageEvent"></my-page>
                        </div>
                    </div>
                </template>
            </my-form-modal>

        </template>
    </my-form-drawer>
</template>

<script src="./js/form.js"></script>
<style scoped src="./css/form.css"></style>
