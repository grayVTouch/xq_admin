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

    'status_for_image_subject' => [
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

    'action_for_collection' => [
        // 收藏
        'collect' ,
        // 取消收藏
        'cancel' ,
    ] ,

    'mode_for_image_subject' => [
        // 收藏
        'strict' ,
        // 取消收藏
        'loose' ,
    ] ,

    'relation_type_for_history' => [
        'image_subject' => '图片专题' ,
        'video_subject' => '视频专题' ,
        'article_subject' => '文章专题' ,
    ] ,

    'relation_type_for_collection' => [
        'image_subject' => '图片专题' ,
        'video_subject' => '视频专题' ,
        'article_subject' => '文章专题' ,
    ] ,

    'relation_type_for_praise' => [
        'image_subject' => '图片专题' ,
        'video_subject' => '视频专题' ,
        'article_subject' => '文章专题' ,
    ] ,

    'relation_type_for_tag' => [
        'image_subject' => '图片专题' ,
        'video_subject' => '视频专题' ,
        'article_subject' => '文章专题' ,
    ] ,

    'platform' => [
        'web' =>'web端' ,
        'app' => 'app' ,
        'android' => 'android' ,
        'ios' => 'ios' ,
        'mobile' => '移动端' ,
    ] ,
];
