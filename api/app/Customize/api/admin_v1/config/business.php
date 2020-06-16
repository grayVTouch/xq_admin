<?php

return [
    // 后台权限类型
    'type_for_admin_permission' => [
        'view' ,
        'api'
    ] ,

    'bool_for_int' => [
        0 => 0 ,
        1 => 1
    ] ,

    'file' => [
        'jpg' ,
        'png' ,
        'bmp' ,
        'jpeg' ,
        'gif' ,
    ] ,

    // 图片专题分类类型
    'type_for_image_subject' => [
        'pro' => '专题' ,
        'misc' => '杂类' ,
    ] ,

    'status_for_image_subject' => [
        -1 => '审核失败',
        0 => '待审核' ,
        1 => '审核成功' ,
    ] ,
];
