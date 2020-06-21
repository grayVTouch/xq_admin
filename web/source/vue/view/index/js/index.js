export default {
    name: "index" ,
    data () {
        return {
            dom: {} ,
            ins: {} ,
        };
    } ,
    mounted () {
        this.initDom();
        this.initIns();
    } ,

    methods: {
        initDom () {
            this.dom.slidebar = G(this.$refs.slidebar);
        } ,

        initIns () {
            this.ins.slidebar = new PicPlay_Touch(this.dom.slidebar.get(0) , {
                // 动画过度时间
                time: 400,
                // 定时器时间
                duration: 5000 ,
                // 索引类型, index-普通索引 image-图片索引 none-无索引
                indexType: 'index',
                // 索引容器位置 (inset | outset)
                indexPos: 'inset',
                // 索引摆放类型（horizontal|vertical）
                placementType: 'horizontal',
                // 索引摆放位置（top|right|bottom|left）
                // placementType = horizontal，则允许的值有 top|bottom；placementType = vertical，则允许的值有 left|right
                placementPos: 'bottom',
                // 默认点击图片会进行跳转
                linkTo: true,
                // 是否启用 上一张 | 下一张 功能
                enableOpr: false,
                // 是否启用滚动功能
                enableScroll: false,
                // 是否开启拖拽功能
                enableDrag: false,
                // 是否开启定时轮播功能
                enableTimer: true ,
                // 初始显示的索引
                index: 1
            })
        } ,
    },
}