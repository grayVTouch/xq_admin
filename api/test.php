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

$img = new ImageProcessor(__DIR__);

$img->compress();
