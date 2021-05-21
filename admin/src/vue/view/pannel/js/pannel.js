import Highcharts from 'highcharts/highstock';

export default {
    name: "pannel" ,
    data () {
        const date = G.getCurTimeData();
        const timestamp = date.year + '-' + (date.month < 10 ? '0' + date.month : date.month) + '-' + (date.date < 10 ? '0' + date.date : date.date);
        return {
            dom: {} ,
            ins: {
                loading: null ,
            } ,
            mode: {
                month: 'single' ,
            } ,
            date: timestamp ,
            year: [] ,
            month: [] ,
            quarter: {
                1: '第一季度' ,
                2: '第二季度' ,
                3: '第三季度' ,
                4: '第四季度' ,
            } ,
            yearForMonth: 0 ,
            monthForMonth: 0 ,
            yearForYear: 0 ,
            yearForQuarter: 0 ,
            quarterForQuarter: 0 ,
            info: {
                user: {} ,
                admin: {} ,
                image_subject: {} ,
                tag: {} ,
                module: {} ,
                video: {} ,
                image_project: {} ,
                video_project: {} ,
                video_series: {} ,
                video_company: {} ,
                category: {} ,
                failed_jobs: {} ,
                processed_video: {} ,
                process_failed_video: {} ,
            } ,
            value: {
                month: null,
                quarter: null,
                year: null,
            } ,
        };
    } ,

    mounted () {
        this.initIns();
        this.init();
        this.getData();
        this.reRender();
    } ,

    methods: {

        initIns () {

        } ,

        init () {
            const yearStart = 1990;
            const d = new Date();
            const curYear = d.getFullYear();
            const curMonth = d.getMonth() + 1;
            const yearEnd = curYear;
            const monthStart = 1;
            const monthEnd = 12;
            let year = [];
            let month = {};
            for (let i = yearStart; i <= yearEnd; ++i)
            {
                year.push(i);
            }
            for (let i = monthStart; i <= monthEnd; ++i)
            {
                month[i] = i + '月份';
            }
            this.year = year;
            this.month = month;
            this.yearForMonth = curYear;
            this.yearForQuarter = curYear;
            this.yearForYear = curYear;
            this.monthForMonth = curMonth;
            this.quarterForQuarter = this.getQuarter(curMonth);
        } ,

        getData () {
            this.pending('getData' , true);
            Api.pannel
                .info()
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    this.info = res.data;
                })
                .finally(() => {
                    this.pending('getData' , false);
                });
        } ,

        reRender () {
            this.$nextTick(() => {
                this.monthChart();
                this.quarterChart();
                this.yearChart();
            });
        } ,

        // 月份统计资料
        monthChart () {
            if (this.myValue.pending.monthChart) {
                this.$info('请求中...请耐心等待');
                return ;
            }
            this.pending('monthChart' , true);
            Api.pannel
                .month({
                    year: this.yearForMonth ,
                    month: this.monthForMonth ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    // 系列
                    let series = res.data;
                        series = Object.values(series);
                    series.forEach((v) => {
                        return v.data = Object.values(v.data);
                    });

                    // 图标绘制
                    const start = 1;
                    const end = this.isNowMonth(this.yearForMonth , this.monthForMonth) ?
                        new Date().getDate() :
                        G.getMonthDays(this.yearForYear , this.yearForMonth);
                    let categories = [];
                    for (let i = start; i <= end; ++i)
                    {
                        categories.push(i);
                    }
                    this.pain({
                        dom: this.$refs.month ,
                        chartType: 'spline' ,
                        title: this.yearForMonth + '年' + this.monthForMonth + '月统计资料' ,
                        // 日期
                        categories ,
                        xTitle: '日期' ,
                        yTitle: '数量' ,
                        plotLine: 100 ,
                        series ,
                    });
                })
                .finally(() => {
                    this.pending('monthChart' , false);
                })
        } ,

        // 月份统计资料
        quarterChart () {
            if (this.myValue.pending.quarterChart) {
                this.$info('请求中...请耐心等待');
                return ;
            }
            this.pending('quarterChart' , true);
            Api.pannel
                .quarter({
                    year: this.yearForQuarter ,
                    quarter: this.quarterForQuarter ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    // 系列
                    let series = res.data;
                    series = Object.values(series);
                    series.forEach((v) => {
                        return v.data = Object.values(v.data);
                    });

                    // 图标绘制
                    const start = 1;
                    const end = this.isNowQuarter(this.yearForQuarter , this.quarterForQuarter) ?
                        new Date().getMonth() + 1 :
                        this.getQuarterEnd(this.quarterForQuarter);
                    let categories = [];
                    for (let i = start; i <= end; ++i)
                    {
                        categories.push(i);
                    }
                    this.pain({
                        dom: this.$refs.quarter ,
                        chartType: 'column' ,
                        title:  this.yearForQuarter + ' 第' + this.quarterForQuarter + '季度 统计资料' ,
                        // 日期
                        categories ,
                        xTitle: '日期' ,
                        yTitle: '数量' ,
                        plotLine: 100 ,
                        series ,
                    });
                })
                .finally(() => {
                    this.pending('quarterChart' , false);
                });
        } ,

        // 年统计资料
        yearChart () {
            if (this.myValue.pending.yearChart) {
                this.$info('请求中...请耐心等待');
                return ;
            }
            this.pending('yearChart' , true);
            Api.pannel
                .year({
                    year: this.yearForYear ,
                })
                .then((res) => {
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandle(res.message);
                        return ;
                    }
                    // 系列
                    let series = res.data;
                    series = Object.values(series);
                    series.forEach((v) => {
                        return v.data = Object.values(v.data);
                    });

                    // 图标绘制
                    const start = 1;
                    const end = this.isNowYear(this.yearForYear) ?
                        new Date().getMonth() + 1 :
                        12;
                    let categories = [];
                    for (let i = start; i <= end; ++i)
                    {
                        categories.push(i);
                    }
                    console.log({
                        dom: this.$refs.year ,
                        chartType: 'column' ,
                        title: this.yearForYear + '年统计资料' ,
                        // 日期
                        categories ,
                        xTitle: '日期' ,
                        yTitle: '数量' ,
                        plotLine: 100 ,
                        series ,
                    });
                    // return ;
                    this.pain({
                        dom: this.$refs.year ,
                        chartType: 'area' ,
                        title: this.yearForYear + '年统计资料' ,
                        // 日期
                        categories ,
                        xTitle: '日期' ,
                        yTitle: '数量' ,
                        plotLine: 100 ,
                        series ,
                    });
                })
                .finally(() => {
                    this.pending('yearChart' , false);
                });
        } ,

        // 获取季度
        getQuarter (month) {
            if ([1,2,3].indexOf(month) != -1) {
                return 1;
            }
            if ([4,5,6].indexOf(month) != -1) {
                return 2;
            }
            if ([7,8,9].indexOf(month) != -1) {
                return 3;
            }
            if ([10,11,12].indexOf(month) != -1) {
                return 4;
            }
            throw new Error('参数 1 类型错误');
        } ,

        getQuarterStart (quarter) {
            switch (quarter) {
                case 1:
                    return 1;
                case 2:
                    return 4;
                case 3:
                    return 7;
                case 4:
                    return 10;
                default:
                    throw new Error('参数 1 错误');
            }
        } ,

        getQuarterEnd (quarter) {
            switch (quarter) {
                case 1:
                    return 3;
                case 2:
                    return 6;
                case 3:
                    return 9;
                case 4:
                    return 12;
                default:
                    throw new Error('参数 1 错误');
            }
        },


        // 判断是否同月
        isNowMonth (year , month) {
            const d = new Date();
            const y = d.getFullYear();
            const m = d.getMonth() + 1;
            return y == year && month == month;
        } ,

        isNowQuarter (year , quarter) {
            const d = new Date();
            const y = d.getFullYear();
            const m = d.getMonth() + 1;
            return y == year && this.getQuarter(m) == quarter;
        } ,

        isNowYear (year) {
            const d = new Date();
            const y = d.getFullYear();
            return y == year;
        } ,

        pain (option) {
            const _default = {
                dom: null ,
                chartType: 'spline' ,
                title: '测试标题' ,
                subtitle: '测试副标题' ,
                plotLine: 10 ,
                categories: ['One' , 'Two' , 'Three'] ,
                xTitle: 'x-测试标题' ,
                yTitle: 'y-测试标题' ,
                series: [
                    {
                        name: '图例-1' ,
                        data: [1,2,3] ,
                    } ,
                    {
                        name: '图例-2' ,
                        data: [3,4,5] ,
                    } ,
                ] ,
                legend: {
                    enabled: true ,
                    layout: 'horizontal' ,
                    align: 'center' ,
                    verticalAlign: 'bottom'
                } ,

            };
            option.legend = option.legend ? option.legend : _default.legend;
            return Highcharts.chart({
                chart: {
                    renderTo: option.dom ,
                    type: option.chartType ,
                } ,
                title: {
                    text: option.title
                } ,
                xAxis: {
                    title: {
                        text: option.xTitle ,
                    } ,
                    categories: option.categories
                } ,
                yAxis: {
                    title: {
                        text: option.yTitle
                    } ,
                    plotLines: [
                        {
                            value: option.plotLine ,
                            dashStyle: 'solid' ,
                            color: 'red' ,
                            width: 1 ,
                            zIndex: 10 ,
                        }
                    ]
                } ,
                series: option.series ,
                legend: {
                    enabled: true ,
                    layout: option.legend.layout ,
                    align: option.legend.align ,
                    verticalAlign: option.legend.verticalAlign
                } ,
                credits: {
                    enabled: false ,
                } ,
            });
        } ,

        setDate (v) {
            this.date = v;
        } ,
    }
};
