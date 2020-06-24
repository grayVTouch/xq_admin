<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\PositionModel;
use stdClass;
use function core\convert_obj;

class PositionHandler extends Handler
{
    public static function handle(?PositionModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        return $res;
    }

}