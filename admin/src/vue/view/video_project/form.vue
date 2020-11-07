<template>
    <my-form-drawer v-model="val.drawer">
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
            <form class="form" @submit.prevent="submitEvent">
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
                            <input type="text" readonly="readonly" :value="`${getUsername(users.current.username , users.current.nickname)}【${users.current.id}】`" class="form-text w-150 run-cursor-not-allow">
                            如需重新搜索，请点击
                            <Button @click="searchUserEvent">搜索</Button>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.user_id }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.module_id}">
                        <td>所属模块</td>
                        <td>
                            <my-select :data="modules" v-model="form.module_id" @change="moduleChanged"></my-select>
                            <span class="need">*</span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.module_id }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.category_id}">
                        <td>所属分类：</td>
                        <td>
                            <my-deep-select :data="categories" v-model="form.category_id" @change="val.error.category_id = ''" :has="false"></my-deep-select>
                            <my-loading v-if="val.pending.getCategories"></my-loading>
                            <span class="need">*</span>
                            <div class="msg">请务必在选择模块后操作</div>
                            <div class="e-msg">{{ val.error.category_id }}</div>
                        </td>
                    </tr>


                    <tr :class="{error: val.error.video_series_id}">
                        <td>视频系列：</td>
                        <td>
                            <input type="text" readonly="readonly" :value="`${videoSeries.current.name}【${videoSeries.current.id}】`" class="form-text w-180 run-cursor-not-allow">
                            如需重新搜索，请点击
                            <Button @click="searchVideoSeriesEvent">搜索</Button>
                            <span class="need"></span>
                            <div class="msg">请务必在选择模块后操作；输入id、名称可查询</div>
                            <div class="e-msg">{{ val.error.video_series_id }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.video_company_id}">
                        <td>视频制作公司：</td>
                        <td>
                            <input type="text" readonly="readonly" :value="`${videoCompany.current.name}【${videoCompany.current.id}】`" class="form-text w-180 run-cursor-not-allow">
                            如需重新搜索，请点击
                            <Button @click="searchVideoCompanyEvent">搜索</Button>
                            <span class="need"></span>
                            <div class="msg">请务必在选择模块后操作；输入id、名称可查询</div>
                            <div class="e-msg">{{ val.error.video_company_id }}</div>
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
                            <div class="msg">请务必在选择用户、模块后操作；输入框按回车键可搜寻已有标签，如不存在会自动创建</div>
                            <div class="e-msg">{{ val.error.tags }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.release_date}">
                        <td>发布日期</td>
                        <td>
                            <i-date-picker type="date" v-model="releaseDate" format="yyyy-MM-dd" @on-change="setReleaseDateEvent" class="iview-form-input"></i-date-picker>
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.release_date }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.end_date}">
                        <td>完结日期</td>
                        <td>
                            <i-date-picker type="date" v-model="endDate" format="yyyy-MM-dd" @on-change="setEndDateEvent" class="iview-form-input"></i-date-picker>
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg">{{ val.error.end_date }}</div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.count}">
                        <td>视频数</td>
                        <td>
                            <input type="number" v-model="form.count" @input="val.error.count = ''" class="form-text">
                            <span class="msg">仅允许整数</span>
                            <span class="need"></span>
                            <span class="e-msg"></span>
                        </td>
                    </tr>

<!--                    <tr :class="{error: val.error.play_count}">-->
<!--                        <td>播放数</td>-->
<!--                        <td>-->
<!--                            <input type="number" v-model="form.play_count" @input="val.error.play_count = ''" class="form-text">-->
<!--                            <span class="msg">仅允许整数</span>-->
<!--                            <span class="need"></span>-->
<!--                            <span class="e-msg"></span>-->
<!--                        </td>-->
<!--                    </tr>-->

                    <tr :class="{error: val.error.score}">
                        <td>评分</td>
                        <td>
<!--                            <input type="number" step="0.01" v-model="form.score" @input="val.error.score = ''" class="form-text">-->
                            <Rate allow-half v-model="form.score" @change="val.error.score = ''" />
<!--                            <span class="msg">精度：0.01 {{ form.score }}</span>-->
                            <span class="need"></span>
                            <div class="msg"></div>
                            <div class="e-msg"></div>
                        </td>
                    </tr>

                    <tr :class="{error: val.error.end_status}">
                        <td>完结状态</td>
                        <td>
                            <RadioGroup v-model="form.end_status">
                                <Radio v-for="(v,k) in $store.state.business.video_project.end_status" :key="k" :label="k">{{ v }}</Radio>
                            </RadioGroup>
                            <span class="need">*</span>
                            <div class="msg">默认：已完结</div>
                            <div class="e-msg">{{ val.error.end_status }}</div>
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

                    <tr :class="{error: val.error.status}">
                        <td>状态</td>
                        <td>
                            <RadioGroup v-model="form.status">
                                <Radio v-for="(v,k) in $store.state.business.video_project.status" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                            </RadioGroup>
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
                            <span class="msg">仅允许整数</span>
                            <span class="need"></span>
                            <span class="e-msg"></span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <button type="submit" v-show="false"></button>
                            <Button v-ripple type="primary" :loading="val.pending.submit" @click="submitEvent"><my-icon icon="tijiao" />提交</Button>
                            <Button v-ripple type="error" @click="closeFormDrawer"><my-icon icon="guanbi" />关闭</Button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </template>

        <!-- 请选择视频系列 -->
        <my-form-modal v-model="val.modalForVideoSeries" title="请选择视频系列" :width="1000">
            <template slot="footer">
                <Button v-ripple type="error" @click="val.modalForVideoSeries=false">取消</Button>
            </template>
            <template slot="default">
                <div class="search-modal">
                    <div class="input">
                        <div class="input-mask"><input type="text" v-model="videoSeries.value" @keyup.enter="searchVideoSeries" placeholder="请输入搜索值"></div>
                        <div class="msg">输入id、名称可查询</div>
                    </div>
                    <div class="list">
                        <Table border  :loading="val.pending.searchVideoSeries" :data="videoSeries.data" :columns="videoSeries.field" @on-row-click="updateVideoSeriesEvent">
                            <template v-slot:thumb="{row,index}"><img :src="row.thumb ? row.thumb : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                            <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                            <template v-slot:action="{row,index}">
                                <my-table-button>选择</my-table-button>
                            </template>
                        </Table>
                    </div>
                    <div class="pager">
                        <my-page :total="videoSeries.total" :limit="videoSeries.limit" :page="videoSeries.page" @on-change="videoSeriesPageEvent"></my-page>
                    </div>
                </div>
            </template>
        </my-form-modal>

        <!-- 请选择视频制作公司 -->
        <my-form-modal v-model="val.modalForVideoCompany" title="请选择视频制作公司" :width="1000">
            <template slot="footer">
                <Button v-ripple type="error" @click="val.modalForVideoCompany=false">取消</Button>
            </template>
            <template slot="default">

                <div class="search-modal">
                    <div class="input">
                        <div class="input-mask"><input type="text" v-model="videoCompany.value" @keyup.enter="searchVideoCompany" placeholder="请输入搜索值"></div>
                        <div class="msg">输入id、名称可查询</div>
                    </div>
                    <div class="list">
                        <Table border  :loading="val.pending.searchVideoCompany" :data="videoCompany.data" :columns="videoCompany.field" @on-row-click="updateVideoCompanyEvent">
                            <template v-slot:thumb="{row,index}"><img :src="row.thumb ? row.thumb : $store.state.context.res.notFound" :height="$store.state.context.table.imageH" class="image"></template>
                            <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                            <template v-slot:action="{row,index}">
                                <my-table-button>选择</my-table-button>
                            </template>
                        </Table>
                    </div>
                    <div class="pager">
                        <my-page :total="videoCompany.total" :limit="videoCompany.limit" :page="videoCompany.page" @on-change="videoCompanyPageEvent"></my-page>
                    </div>
                </div>
            </template>
        </my-form-modal>

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


    </my-form-drawer>
</template>

<script src="./js/form.js"></script>

<style scoped src="./css/form.css"></style>
