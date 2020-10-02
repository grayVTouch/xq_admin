<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ModuleModel;
use stdClass;
use function api\admin\get_value;
use function core\convert_obj;

class ModuleHandler extends Handler
{
    public static function handle(?ModuleModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $res->__enable__ = get_value('business.bool_for_int' , $res->enable);

        return $res;
    }

}
