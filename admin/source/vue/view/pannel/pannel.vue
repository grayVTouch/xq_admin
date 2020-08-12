<template>
    <my-base :topRoute="topRoute" :position="position">
        <div class="c-component">
<!--            <navigation :topRoute="topRoute" :position="position"></navigation>-->
            <div class="content">
                <div class="in">

                    <div class="welcome">
                        <div class="top">欢迎回来 <b>{{ $store.state.user.username }}</b></div>
                        <div class="btm">最近一次登录时间： <b>{{ $store.state.user.last_time }}</b> 登录IP: <b>{{ $store.state.user.last_ip }}</b></div>
                    </div>

                    <div class="today">
                        <div class="run-title">
                            <div class="left">今日统计资料</div>
                            <div class="right"></div>
                        </div>
                        <div class="list">

                            <div class="card">
                                <div class="left"><my-icon icon="yonghu2"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增平台用户</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.user.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.user.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.user.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="yonghu5"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增后台用户</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.admin.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.admin.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.admin.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="tupian1"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增图片专题</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.image_subject.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.image_subject.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.image_subject.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="guanlian1"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增关联主体</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.subject.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.subject.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.subject.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="biaoqian"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增标签</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.tag.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.tag.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.tag.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="gonggongmokuai"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增模块</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.module.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.module.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.module.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="shipin"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增视频</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.video.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.video.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.video.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="shipin"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增视频专题</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.video_subject.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.video_subject.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.video_subject.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="shipin"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增视频系列</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.video_series.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.video_series.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.video_series.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="left"><my-icon icon="gongsi"></my-icon></div>
                                <div class="right">
                                    <div class="top">新增视频制作公司</div>
                                    <div class="btm">
                                        <div class="line">今日：<b>{{ info.video_company.today }}</b></div>
                                        <div class="line">昨日：<b>{{ info.video_company.yesterday }}</b></div>
                                        <div class="line ratio" :class="{up: info.user.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.video_company.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="history">
                        <div class="run-title">
                            <div class="left">历史统计资料 </div>
                            <div class="right"></div>
                        </div>
                        <div class="list">

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="yonghu2"></my-icon></div>
                                <div class="right">
                                    <div class="top">用户数</div>
                                    <div class="btm">{{ info.user.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="yonghu5"></my-icon></div>
                                <div class="right">
                                    <div class="top">后台用户数</div>
                                    <div class="btm">{{ info.admin.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="tupian1"></my-icon></div>
                                <div class="right">
                                    <div class="top">图片专题数</div>
                                    <div class="btm">{{ info.image_subject.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="guanlian1"></my-icon></div>
                                <div class="right">
                                    <div class="top">关联主体数</div>
                                    <div class="btm">{{ info.subject.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="biaoqian"></my-icon></div>
                                <div class="right">
                                    <div class="top">标签数</div>
                                    <div class="btm">{{ info.tag.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="gonggongmokuai"></my-icon></div>
                                <div class="right">
                                    <div class="top">模块数</div>
                                    <div class="btm">{{ info.module.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="shipin"></my-icon></div>
                                <div class="right">
                                    <div class="top">视频数</div>
                                    <div class="btm">{{ info.video.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="shipin"></my-icon></div>
                                <div class="right">
                                    <div class="top">视频专题数</div>
                                    <div class="btm">{{ info.video_subject.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="shipin"></my-icon></div>
                                <div class="right">
                                    <div class="top">视频系列数</div>
                                    <div class="btm">{{ info.video_series.total }}</div>
                                </div>
                            </div>

                            <div class="card-for-total">
                                <div class="left"><my-icon icon="gongsi"></my-icon></div>
                                <div class="right">
                                    <div class="top">视频制作公司数</div>
                                    <div class="btm">{{ info.video_company.total }}</div>
                                </div>
                            </div>



                        </div>
                    </div>

<!--                    <div class="chart">-->

<!--                        &lt;!&ndash;<div class="top hide">&ndash;&gt;-->

<!--                        &lt;!&ndash;<div class="month chart-box">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="run-title">&ndash;&gt;-->

<!--                        &lt;!&ndash;<div class="left">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="title"><img src="./image/month.png" class="image">月统计资料</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="year">&ndash;&gt;-->
<!--                        &lt;!&ndash;年份：&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-select v-model="yearForMonth" style="width: 80px;">&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-option v-for="v in year" :key="v" :value="v">{{ v }}</i-option>&ndash;&gt;-->
<!--                        &lt;!&ndash;</i-select>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="month single">&ndash;&gt;-->
<!--                        &lt;!&ndash;月份：&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-select v-model="monthForMonth" style="width: 80px;">&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-option v-for="(v,k) in month" :key="k" :value="parseInt(k)">{{ v }}</i-option>&ndash;&gt;-->
<!--                        &lt;!&ndash;</i-select>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="btns">&ndash;&gt;-->
<!--                        &lt;!&ndash;<v-button color="blue" @click="monthChart">确定</v-button>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="right"></div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="con">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="chart-container" ref="month"></div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<v-loading ref="month-loading"></v-loading>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->

<!--                        &lt;!&ndash;<div class="quarter chart-box">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="run-title">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="left">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="title"><img src="./image/quarter.png" class="image">季度统计资料</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="year">&ndash;&gt;-->
<!--                        &lt;!&ndash;年份：&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-select v-model="yearForQuarter" style="width: 80px;">&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-option v-for="v in year" :key="v" :value="v">{{ v }}</i-option>&ndash;&gt;-->
<!--                        &lt;!&ndash;</i-select>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="month single">&ndash;&gt;-->
<!--                        &lt;!&ndash;季度：&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-select v-model="quarterForQuarter" style="width: 100px;">&ndash;&gt;-->
<!--                        &lt;!&ndash;<i-option v-for="(v,k) in quarter" :key="k" :value="parseInt(k)">{{ v }}</i-option>&ndash;&gt;-->
<!--                        &lt;!&ndash;</i-select>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="btns">&ndash;&gt;-->
<!--                        &lt;!&ndash;<v-button color="blue" @click="quarterChart">确定</v-button>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="right"></div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="con">&ndash;&gt;-->
<!--                        &lt;!&ndash;<div class="chart-container" ref="quarter"></div>&ndash;&gt;-->
<!--                        &lt;!&ndash;<v-loading ref="quarter-loading"></v-loading>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->
<!--                        &lt;!&ndash;</div>&ndash;&gt;-->

<!--                        <div class="btm chart-box">-->
<!--                            <div class="run-title">-->
<!--                                <div class="left">-->
<!--                                    <div class="title"><img src="./image/year.png" class="image">用户活跃度</div>-->
<!--                                    <div class="year">-->
<!--                                        日期：-->
<!--                                        <DatePicker type="date" placement="bottom-end" placeholder="请选择日期" style="width: 200px" @on-change="setDate"></DatePicker>-->
<!--                                    </div>-->
<!--                                    <div class="btns">-->
<!--                                        <v-button color="blue" @click="chartForStatisUserActivityLog">确定</v-button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="right"></div>-->
<!--                            </div>-->
<!--                            <div class="con">-->
<!--                                <div class="chart-container" ref="statistics-user-activity-log"></div>-->
<!--                                <v-loading ref="statistics-user-activity-log-loading"></v-loading>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="system">
                        <div class="run-title">
                            <div class="left">系统信息</div>
                            <div class="right"></div>
                        </div>
                        <table class="table line-table">
                            <tbody>
                            <tr>
                                <td>系统版本</td>
                                <td>1.0.1</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>作者</td>
                                <td>running</td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<!--            <v-loading ref="loading"></v-loading>-->
        </div>
    </my-base>
</template>

<script src="./js/index.js"></script>
<!--<style src="../public/css/global.css"></style>-->
<!--<style scoped src="../public/css/public.css"></style>-->
<style scoped src="./css/index.css"></style>