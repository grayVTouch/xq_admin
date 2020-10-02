<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ImageModel;
use App\Customize\api\web\util\FileUtil;
use stdClass;
use function core\convert_obj;

class ImageHandler extends Handler
{
    public static function handle(?ImageModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        return $res;
    }

}
