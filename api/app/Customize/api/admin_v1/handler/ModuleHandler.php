<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ModuleModel;
use stdClass;
use function api\admin_v1\get_value;
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
