<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\PositionModel;
use stdClass;
use function api\web_v1\get_value;
use function core\convert_obj;

class PositionHandler extends Handler
{
    public static function handle(?PositionModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $res->__platform__ = get_value('business.platform' , $res->platform);

        return $res;
    }

}
