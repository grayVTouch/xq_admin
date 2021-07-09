<?php


//$total = 100;
//for ($i = 1; $i <= $total; $i++) {
//    // \r - return 移动到当前行的最左边
//    // \n - newline 新建一行
//    printf("progress: [%-50s] %d%% Done\r", '###' , $i/$total*100);
//    usleep(100 * 1000);
//}
//echo "\n";
//echo "Done!\n";

use Core\Lib\ImageProcessor;

require __DIR__ . '/app/Customize/api/admin/plugin/extra/app.php' ;
//
//$img = new ImageProcessor(__DIR__);
//
//$file = __DIR__ . '/high【预览】.png';
//$file1 = __DIR__ . '/20210705230804dBqvhe.jpeg';
//$file2 = __DIR__ . '/001【预览】.png';
//
////$c_file = $img->compress($file1 , [
////    'mode'      => 'ratio' ,
////    'ratio'     => 1 ,
////] , false);
////
////var_dump(filesize($file1));
////var_dump(filesize($c_file));
//
//$info = getimagesize($file2);
//
////print_r($info);
//
//$w = $info[0];
//$h = $info[1];
//
//$cav = imagecreatetruecolor($w , $h);
//$src = imagecreatefrompng($file2);
//imagecopyresampled($cav , $src , 0 , 0 , 0 , 0 , $w , $h , $w , $h);
//
//imagewebp($cav , __DIR__ . '/001【预览】1.webp' , 75);

//$extension = 'webp';
//$image = 'd:/file/test.png';
//$point_last_position = mb_strrpos($image , '.');
//$target = mb_substr($image , 0 , $point_last_position);
//var_dump($target);
//$target .= '.' . $extension;
//var_dump($target);

$image = __DIR__ . '/007【预览】.jpeg';

//$image = __DIR__ . '/萌奈子 2【1】.jpg';
//
//ImageProcessor::originCompress($image , 'webp' , 75);


//rename("G:/resource//专题图片/萌奈子/萌奈子【1】.jpg","G:/resource//专题图片/萌奈子/萌奈子【1】.jpg");

//ImageProcessor::originCompress($image , 'webp' , 100);
ImageProcessor::originCompress($image , 'jpeg' , 50);

//ImageProcessor::originCompress($image , 'jpeg' , 100);
