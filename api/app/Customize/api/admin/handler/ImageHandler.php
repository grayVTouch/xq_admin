<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\util\FileUtil;
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
