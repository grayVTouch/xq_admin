<template>
    <div class="pic-preview-async-container">
        <div class="pic-preview-async">
            <div class="preview">

                <div class="header">
                    <div class="info"></div>
                    <div class="action">
                        <button title="幻灯片" class="play"><i class="run-iconfont run-iconfont-70BasicIcons-all-64"></i></button>
                        <button title="幻灯片" class="pause"><i class="run-iconfont run-iconfont-stopcircletingzhi f-19"></i></button>
                        <button title="查看原图" class="view-origin-image"><i class="run-iconfont run-iconfont-chakan1 f-17"></i></button>
                        <button title="还原" class="origin"><i class="run-iconfont run-iconfont-huanyuan"></i></button>
                        <button title="缩小" class="shrink"><i class="run-iconfont run-iconfont-suoxiao"></i></button>
                        <button title="放大" class="grow"><i class="run-iconfont run-iconfont-fangda-copy"></i></button>
                        <button title="全屏" class="full-screen"><i class="run-iconfont run-iconfont-quanping1"></i></button>
                        <button title="退出全屏" class="normal-screen"><i class="run-iconfont run-iconfont-quanping"></i></button>
                        <button title="显示列表" class="spread"><i class="run-iconfont run-iconfont-weibiaoti26"></i></button>
                        <button title="隐藏列表" class="narrow"><i class="run-iconfont run-iconfont-daohangzhankai-"></i></button>
                        <button title="关闭" class="close"><i class="run-iconfont run-iconfont-close"></i></button>
                    </div>
                </div>

                <div class="content">
                    <div class="b-images">
                        <!--                            <div class="item" v-for="v in data.images"><img :src="v.src" class="image" alt=""></div>-->
                    </div>
                    <div class="action prev"><i class="run-iconfont run-iconfont-houtui"></i></div>
                    <div class="action next"><i class="run-iconfont run-iconfont-qianjin"></i></div>
                </div>
            </div>

            <div class="s-images">
                <div class="title m-b-15">
                    <div class="name">图片列表</div>
                    <div class="action">
                        <button class="close"><i class="run-iconfont run-iconfont-close"></i></button>
                    </div>
                </div>
                <div class="list">
                    <!--                <div class="item cur"><img src="./res/01.jpg" class="image" alt=""></div>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "pic_preview"
    }
</script>

<style scoped>

</style>
