<?php

return [
    // 后台权限类型
    'type_for_admin_permission' => [
        'view' ,
        'api'
    ] ,

    'bool_for_int' => [
        0 => '否' ,
        1 => '是'
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

    // 图片专题分类类型
    'type_for_video' => [
        'pro' => '专题' ,
        'misc' => '杂类' ,
    ] ,

    'status_for_image_subject' => [
        -1 => '审核失败',
        0 => '待审核' ,
        1 => '审核成功' ,
    ] ,

    'status_for_video' => [
        -1 => '审核失败',
        0 => '待审核' ,
        1 => '审核成功' ,
    ] ,

    'sex_for_user' => [
        'male' => '男' ,
        'female' => '女' ,
        'secret' => '保密' ,
        'both' => '两性' ,
        'shemale' => '人妖' ,
    ] ,

    'platform' => [
        'web' =>'web端' ,
        'app' => 'app' ,
        'android' => 'android' ,
        'ios' => 'ios' ,
        'mobile' => '移动端' ,
    ] ,

    'mode_for_file' => [
        'ratio' ,
        'fix' ,
        'fix-width' ,
        'fix-height' ,
    ] ,

    'process_status_for_video' => [
        -1 => '处理失败' ,
        0 => '信息处理中' ,
        1 => '转码中' ,
        2 => '处理完成' ,
    ] ,

    'status_for_video_subject' => [
        'making' => '连载中' ,
        'completed' => '已完结' ,
        'terminated' => '已终止' ,
    ] ,

    'type_for_region' => [
        'country'   => '国家' ,
        'state'     => '州|邦|省' ,
        'region'    => '地区' ,
    ] ,

    'os_for_disk' => [
        'windows' => 'windows' ,
        'linux' => 'linux' ,
        'mac' => 'mac' ,
    ] ,
];
