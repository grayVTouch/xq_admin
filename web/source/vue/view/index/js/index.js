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

    beforeRouteEnter (to, from, next) {
        // 在渲染该组件的对应路由被 confirm 前调用
        // 不！能！获取组件实例 `this`
        // 因为当守卫执行前，组件实例还没被创建
        next();
    },
    beforeRouteUpdate (to, from, next) {
        // 在当前路由改变，但是该组件被复用时调用
        // 举例来说，对于一个带有动态参数的路径 /foo/:id，在 /foo/1 和 /foo/2 之间跳转的时候，
        // 由于会渲染同样的 Foo 组件，因此组件实例会被复用。而这个钩子就会在这个情况下被调用。
        // 可以访问组件实例 `this`
        this.ins.slidebar.setTime();
        next();
    },
    beforeRouteLeave (to, from, next) {
        // 导航离开该组件的对应路由时调用
        // 可以访问组件实例 `this`
        this.ins.slidebar.clearTime();
        next();
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