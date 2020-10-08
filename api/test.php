<?php

use Core\Lib\File;

require_once __DIR__ . '/app/Customize/api/admin/plugin/extra/app.php';

$string = 'd:/web/xinqu/resource/upload/初音岛 第一季/初音岛 第一季';


function generateMediaSuffix(string $type , string $name , string $extension): string
{
    return $type === 'pro' ? $name . '.' . $extension : $name . '【' . random(8 , 'letter' , true) . '】' . '.' . $extension;
}

$res = generateMediaSuffix('pro' , '测试视频' , 'jpeg');

var_dump($res);
