<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ModuleModel;
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
