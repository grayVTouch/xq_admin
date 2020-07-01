<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\TagModel;
use stdClass;
use function core\convert_obj;

class TagHandler extends Handler
{
    public static function handle(?TagModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $res->module = $module;
        return $res;
    }

}
