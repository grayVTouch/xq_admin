<?php

return [
    'limit' => 20 ,

    // 预览视频截取段数
    'video_simple_preview' => [
        'count' => 4 ,

        'duration' => 1.5
    ] ,

    // 视频预览单张间隔时间
    'video_preview' => [
        // 间隔 2s 截取一张
        'duration' => 2 ,
        // 单张图片尺寸：宽
        'width' => 320 ,
        // 单张图片尺寸：高
        'height' => 160 ,
    ] ,

    // 视频处理后文件临时存放地址
    'video_temp_dir' => storage_path('app/video') ,
];
