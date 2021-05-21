<template>
    <div class="c-component">
        <!--            <navigation :topRoute="topRoute" :position="position"></navigation>-->
        <div class="content">
            <div class="in">

                <div class="welcome">
                    <div class="top">欢迎回来 <b>{{ user().username }}</b></div>
                    <div class="btm">最近一次登录时间： <b>{{ user().last_time }}</b> 登录IP: <b>{{ user().last_ip }}</b></div>
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
                                    <div class="line ratio" :class="{up: info.admin.flag == 'up' , down: info.admin.flag == 'down'}">相较昨天：<b>{{ info.admin.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="left"><my-icon icon="tupian1"></my-icon></div>
                            <div class="right">
                                <div class="top">新增图片专题</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.image_project.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.image_project.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.image_project.flag == 'up' , down: info.image_project.flag == 'down'}">相较昨天：<b>{{ info.image_project.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="left"><my-icon icon="guanlian1"></my-icon></div>
                            <div class="right">
                                <div class="top">新增图片主体</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.image_subject.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.image_subject.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.image_subject.flag == 'up' , down: info.image_subject.flag == 'down'}">相较昨天：<b>{{ info.image_subject.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>

<!--                        <div class="card">-->
<!--                            <div class="left"><my-icon icon="biaoqian"></my-icon></div>-->
<!--                            <div class="right">-->
<!--                                <div class="top">新增标签</div>-->
<!--                                <div class="btm">-->
<!--                                    <div class="line">今日：<b>{{ info.tag.today }}</b></div>-->
<!--                                    <div class="line">昨日：<b>{{ info.tag.yesterday }}</b></div>-->
<!--                                    <div class="line ratio" :class="{up: info.tag.flag == 'up' , down: info.tag.flag == 'down'}">相较昨天：<b>{{ info.tag.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="card">-->
<!--                            <div class="left"><my-icon icon="gonggongmokuai"></my-icon></div>-->
<!--                            <div class="right">-->
<!--                                <div class="top">新增模块</div>-->
<!--                                <div class="btm">-->
<!--                                    <div class="line">今日：<b>{{ info.module.today }}</b></div>-->
<!--                                    <div class="line">昨日：<b>{{ info.module.yesterday }}</b></div>-->
<!--                                    <div class="line ratio" :class="{up: info.module.flag == 'up' , down: info.module.flag == 'down'}">相较昨天：<b>{{ info.module.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="card">
                            <div class="left"><my-icon icon="shipin"></my-icon></div>
                            <div class="right">
                                <div class="top">新增视频</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.video.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.video.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.video.flag == 'up' , down: info.video.flag == 'down'}">相较昨天：<b>{{ info.video.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="left"><my-icon icon="shipin"></my-icon></div>
                            <div class="right">
                                <div class="top">新增视频专题</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.video_project.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.video_project.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.video_project.flag == 'up' , down: info.video_project.flag == 'down'}">相较昨天：<b>{{ info.video_project.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
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
                                    <div class="line ratio" :class="{up: info.video_series.flag == 'up' , down: info.video_series.flag == 'down'}">相较昨天：<b>{{ info.video_series.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
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
                                    <div class="line ratio" :class="{up: info.video_company.flag == 'up' , down: info.video_company.flag == 'down'}">相较昨天：<b>{{ info.video_company.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>

<!--                        <div class="card">-->
<!--                            <div class="left"><my-icon icon="leimupinleifenleileibie"></my-icon></div>-->
<!--                            <div class="right">-->
<!--                                <div class="top">新增内容分类</div>-->
<!--                                <div class="btm">-->
<!--                                    <div class="line">今日：<b>{{ info.category.today }}</b></div>-->
<!--                                    <div class="line">昨日：<b>{{ info.category.yesterday }}</b></div>-->
<!--                                    <div class="line ratio" :class="{up: info.category.flag == 'up' , down: info.user.flag == 'down'}">相较昨天：<b>{{ info.category.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="card">
                            <div class="left"><my-icon icon="guanlian"></my-icon></div>
                            <div class="right">
                                <div class="top">新增失败队列</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.failed_jobs.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.failed_jobs.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.failed_jobs.flag == 'up' , down: info.failed_jobs.flag == 'down'}">相较昨天：<b>{{ info.failed_jobs.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="left"><my-icon icon="shipin"></my-icon></div>
                            <div class="right">
                                <div class="top">新增处理完成视频</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.processed_video.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.processed_video.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.processed_video.flag == 'up' , down: info.processed_video.flag == 'down'}">相较昨天：<b>{{ info.processed_video.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="left"><my-icon icon="shipin"></my-icon></div>
                            <div class="right">
                                <div class="top">新增处理失败视频</div>
                                <div class="btm">
                                    <div class="line">今日：<b>{{ info.process_failed_video.today }}</b></div>
                                    <div class="line">昨日：<b>{{ info.process_failed_video.yesterday }}</b></div>
                                    <div class="line ratio" :class="{up: info.process_failed_video.flag == 'up' , down: info.process_failed_video.flag == 'down'}">相较昨天：<b>{{ info.process_failed_video.ratio }}</b><my-icon icon="web-icon-"></my-icon></div>
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
                                <div class="btm">{{ info.image_project.total }}</div>
                            </div>
                        </div>

                        <div class="card-for-total">
                            <div class="left"><my-icon icon="guanlian1"></my-icon></div>
                            <div class="right">
                                <div class="top">图片主体</div>
                                <div class="btm">{{ info.image_subject.total }}</div>
                            </div>
                        </div>

<!--                        <div class="card-for-total">-->
<!--                            <div class="left"><my-icon icon="biaoqian"></my-icon></div>-->
<!--                            <div class="right">-->
<!--                                <div class="top">标签数</div>-->
<!--                                <div class="btm">{{ info.tag.total }}</div>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="card-for-total">-->
<!--                            <div class="left"><my-icon icon="gonggongmokuai"></my-icon></div>-->
<!--                            <div class="right">-->
<!--                                <div class="top">模块数</div>-->
<!--                                <div class="btm">{{ info.module.total }}</div>-->
<!--                            </div>-->
<!--                        </div>-->

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
                                <div class="btm">{{ info.video_project.total }}</div>
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

<!--                        <div class="card-for-total">-->
<!--                            <div class="left"><my-icon icon="leimupinleifenleileibie"></my-icon></div>-->
<!--                            <div class="right">-->
<!--                                <div class="top">内容分类数</div>-->
<!--                                <div class="btm">{{ info.category.total }}</div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="card-for-total">
                            <div class="left"><my-icon icon="guanlian"></my-icon></div>
                            <div class="right">
                                <div class="top">失败队列数</div>
                                <div class="btm">{{ info.failed_jobs.total }}</div>
                            </div>
                        </div>

                        <div class="card-for-total">
                            <div class="left"><my-icon icon="shipin"></my-icon></div>
                            <div class="right">
                                <div class="top">处理完成视频数</div>
                                <div class="btm">{{ info.processed_video.total }}</div>
                            </div>
                        </div>

                        <div class="card-for-total">
                            <div class="left"><my-icon icon="shipin"></my-icon></div>
                            <div class="right">
                                <div class="top">处理失败视频数</div>
                                <div class="btm">{{ info.process_failed_video.total }}</div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="chart">

                    <div class="top">

                        <div class="month chart-box">
                            <div class="run-title">
                                <div class="left">
                                    <div class="title"><my-icon icon="yuetongji" class="f-24 run-gray m-r-10"></my-icon>月统计资料</div>
                                    <div class="year">
                                        年份：
                                        <i-select v-model="yearForMonth" style="width: 80px;">
                                            <i-option v-for="v in year" :key="v" :value="v">{{ v }}</i-option>
                                        </i-select>
                                    </div>
                                    <div class="month single">
                                        月份：
                                        <i-select v-model="monthForMonth" style="width: 80px;">
                                            <i-option v-for="(v,k) in month" :key="k" :value="parseInt(k)">{{ v }}
                                            </i-option>
                                        </i-select>
                                    </div>
                                    <div class="btns">
                                        <i-button type="primary" size="small" :loading="myValue.pending.monthChart" @click="monthChart">确定</i-button>
                                    </div>
                                </div>
                                <div class="right"></div>
                            </div>
                            <div class="con">
                                <div class="chart-container" ref="month"></div>
                            </div>
                        </div>

                        <div class="quarter chart-box">
                            <div class="run-title">
                                <div class="left">
                                    <div class="title"><my-icon icon="jidu" class="f-24 run-gray m-r-10"></my-icon>季度统计资料</div>
                                    <div class="year">
                                        年份：
                                        <i-select v-model="yearForQuarter" style="width: 80px;">
                                            <i-option v-for="v in year" :key="v" :value="v">{{ v }}</i-option>
                                        </i-select>
                                    </div>
                                    <div class="month single">
                                        季度：
                                        <i-select v-model="quarterForQuarter" style="width: 100px;">
                                            <i-option v-for="(v,k) in quarter" :key="k" :value="parseInt(k)">{{ v }}
                                            </i-option>
                                        </i-select>
                                    </div>
                                    <div class="btns">
                                        <i-button type="primary" size="small" :loading="myValue.pending.quarterChart" @click="quarterChart">确定</i-button>
                                    </div>
                                </div>
                                <div class="right"></div>
                            </div>
                            <div class="con">
                                <div class="chart-container" ref="quarter"></div>
<!--                                <v-loading ref="quarter-loading"></v-loading>-->
                            </div>
                        </div>

                    </div>

                    <div class="btm chart-box">
                        <div class="run-title">
                            <div class="left">
                                <div class="title"><my-icon icon="niandu" class="f-24 run-gray m-r-10"></my-icon>年度统计资料</div>
                                <div class="year">
                                    年份：
                                    <i-date-picker type="year" placement="bottom-end" placeholder="请选择年份" style="width: 200px" @on-change="setDate"></i-date-picker>
                                </div>
                                <div class="btns">
                                    <i-button type="primary" size="small" :loading="myValue.pending.yearChart" @click="yearChart">确定</i-button>
                                </div>
                            </div>
                            <div class="right"></div>
                        </div>
                        <div class="con">
                            <div class="chart-container" ref="year"></div>
<!--                            <v-loading ref="statistics-user-activity-log-loading"></v-loading>-->
                        </div>
                    </div>
                </div>

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
    </div>

</template>

<script src="./js/pannel.js"></script>
<style scoped src="./css/pannel.css"></style>
