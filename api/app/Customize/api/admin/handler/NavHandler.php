<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\NavModel;
use App\Customize\api\admin\model\ModuleModel;
use stdClass;
use function api\admin\get_value;
use function core\convert_obj;

class NavHandler extends Handler
{
    public static function handle(?NavModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        if ($deep) {
            $nav = $res->p_id ? NavModel::find($res->p_id) : null;
            $nav = self::handle($nav , false);
        } else {
            $nav = null;
        }
        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $res->module = $module;
        $res->nav = $nav;

        $res->__platform__ = get_value('business.platform' , $res->platform);

        return $res;
    }


}
