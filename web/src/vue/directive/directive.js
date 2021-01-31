/**
 * ************************
 * 全局自定义指令
 * ************************
 */
Vue.directive('ripple', {
    // 当被绑定的元素插入到 DOM 中时……
    inserted: function (el) {
        new TouchFeedback_Transform(el , {

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

        const pW = img.parent().width();
        const pH = img.parent().height();

        // 容器尺寸
        img.data('p-width' , pW);
        img.data('p-height' , pH);

        // 原图尺寸
        img.data('width' , w);
        img.data('height' , h);

        const pRatio = pW / pH;
        const squareRatio = 1;
        const ratio = w / h;
        const eScaleH = pW / w * h;
        if (pRatio > squareRatio) {
            // 水平-矩形
            if (pRatio > 1.5) {
                img.addClass('horizontal-for-img');
            } else {
                // 当长款比例小于这个值的时候，将其当成是正方形处理
                if (ratio > squareRatio) {
                    img.addClass('vertical-for-img');
                } else if (ratio < squareRatio) {
                    img.addClass('horizontal-for-img');
                } else {
                    img.addClass('vertical-for-img');
                }
            }
        } else if (pRatio < squareRatio) {
            // 垂直-矩形
            if (h > pH) {
                if (eScaleH < pH) {
                    img.addClass('vertical-for-img');
                } else {
                    img.addClass('horizontal-for-img');
                }
            } else if (h < pH) {
                img.addClass('vertical-for-img');
            } else {
                img.addClass('vertical-for-img');
            }
        } else {
            // 正方形
            if (ratio > squareRatio) {
                img.addClass('vertical-for-img');
            } else if (ratio < squareRatio) {
                img.addClass('horizontal-for-img');
            } else {
                img.addClass('vertical-for-img');
            }
        }
    };
    imgCopy.src = src;
};

Vue.directive('judge-img-size', {
    // 插入
    inserted: judgeImgSize ,
});

// 复制功能
Vue.directive('clipboard' , {
    inserted (dom) {
        new ClipboardJS(dom);
    }
});
