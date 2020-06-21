<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\ModuleModel;
use stdClass;
use function core\convert_obj;

class ModuleHandler extends Handler
{
    public static function handle(?ModuleModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        return $res;
    }

}
