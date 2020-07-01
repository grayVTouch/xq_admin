<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\RelationTagModel;
use stdClass;
use function core\convert_obj;

class RelationTagHandler extends Handler
{
    public static function handle(?RelationTagModel $model): ?stdClass
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
