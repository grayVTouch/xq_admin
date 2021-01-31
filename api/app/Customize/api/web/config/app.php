<?php

return [
    'limit' => 20 ,
    // 单位：ms
    'code_duration' => 10 * 60 ,

    // 资源URL：disk=local 必须提供
    'res_url' => 'http://res.xq.test' ,

    // 资源的映射目录：disk=local 必须提供
    'res_dir' => base_path() . '/../resource' ,

    /**
     * 当前的磁盘存储方式
     *
     * local - 本地磁盘
     * cloud - 云存储
     */
    'disk' => 'local' ,

    // 相关目录
    'dir' => [
        'image'         => '非专题图片' ,
        'video'         => '非专题视频' ,
        'system'        => '系统资源' ,
        'image_project' => '专题图片' ,
        'video_project' => '专题视频' ,
    ] ,

];
