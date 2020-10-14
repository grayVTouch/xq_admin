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
        // alert('nice');
        this.initIns();
        this.init();
        this.getData();
        this.reRender();
    } ,

    methods: {
        initIns () {

        } ,

        init () {

        } ,

        getData () {
            this.pending('getData' , true);
            Api.pannel.info((msg , data , code) => {
                this.pending('getData' , false);
                if (code != TopContext.code.Success) {
                    this.message('error' , msg);
                    return ;
                }
                this.info = data;

            });
        } ,

        reRender () {
            this.$nextTick(() => {
                // this.monthChart();
                // this.quarterChart();
                // this.chartForStatisUserActivityLog();
            });
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

        // 用户活跃度统计
        chartForStatisUserActivityLog () {
            this.ins.loadingForStatisticsUserActivityLog.show();
            Api.pannel.statisticsUserActivityLog({
                date: this.date,
            }, (res, code) => {
                this.ins.loadingForStatisticsUserActivityLog.hide();
                if (code != topContext.successCode) {
                    this.alert(res);
                    return;
                }
                let categories = [];
                let series = [
                    {
                        name: '在线用户数',
                        data: [],
                    },
                    {
                        name: '在线客户端数',
                        data: []
                    }
                ];
                // console.log(res);
                res.forEach((v) => {
                    categories.push(v.time_point);
                    series[0].data.push(parseInt(v.user_count));
                    series[1].data.push(parseInt(v.client_count));
                });
                // console.log(categories , series);
                this.pain({
                    dom: this.$refs['statistics-user-activity-log'],
                    // chartType: '',
                    title: this.date + ' 统计资料',
                    categories,
                    xTitle: '时间',
                    yTitle: '数量',
                    plotLine: 100,
                    series,
                })
            });
        }
    }
};
