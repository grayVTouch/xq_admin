<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\VideoSrcModel;
use App\Customize\api\web\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoSrcHandler extends Handler
{
    public static function handle(?VideoSrcModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        return $res;
    }

}
