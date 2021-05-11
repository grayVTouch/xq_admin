<?php

use Core\Lib\File;

//
//require_once __DIR__ . '/app/Customize/api/admin/plugin/extra/app.php';
//
////$string = 'd:/web/xinqu/resource/upload/初音岛 第一季/初音岛 第一季';
//
//$string = 'd:/web/xinqu/resource/upload/系统资源/20210110/20210110183644ZkBKIv.jpg';
////
////function generateMediaSuffix(string $type , string $name , string $extension): string
////{
////    return $type === 'pro' ? $name . '.' . $extension : $name . '【' . random(8 , 'letter' , true) . '】' . '.' . $extension;
////}
////
////$res = generateMediaSuffix('pro' , '测试视频' , 'jpeg');
////
////var_dump($res);
//
//$image_processor = new \Core\Lib\ImageProcessor(__DIR__);
//$res = $image_processor->compress('d:/web/xinqu/resource/upload/系统资源/20210110/20210110183644ZkBKIv.jpg' , [
//    'mode' => 'fix-width' ,
//    'width' => 960
//] , false);
//
//var_dump($res);


$res = scandir('/');

print_r($res);
