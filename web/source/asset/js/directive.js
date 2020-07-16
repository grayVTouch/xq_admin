/**
 * ************************
 * 全局自定义指令
 * ************************
 */
Vue.directive('ripple', {
    // 当被绑定的元素插入到 DOM 中时……
    inserted: function (el) {
        new TouchFeedback(el , {

        });
    }
});


// 图片调整
const judgeImgSize = (img) => {
    img = G(img);
    const src= img.native('src');
    if (!G.isValid(src)) {
        return ;
    }
    const imgCopy = new Image();
    imgCopy.onload = function(){
        const w = this.width;
        const h = this.height;
        if (w > h) {
            img.addClass('horizontal-for-img');
        } else {
            img.addClass('vertical-for-img');
        }
    };
    imgCopy.src = src;
};

Vue.directive('judge-img-size', {
    // 插入
    inserted (img) {
        img = G(img);
        // img.addClass('judge-img-size');
        judgeImgSize(img.get(0));
    } ,

    // 更新
    update: judgeImgSize
});

