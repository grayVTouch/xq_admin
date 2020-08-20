<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\VideoModel;
use stdClass;
use function core\convert_obj;

class VideoHandler extends Handler
{
    public static function handle(?VideoModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_obj($model);

        return $model;
    }
}
