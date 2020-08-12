<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ImageAtPositionModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\PositionModel;
use App\Customize\api\admin_v1\util\FileUtil;
use stdClass;
use function api\admin_v1\get_value;
use function core\convert_obj;

class ImageAtPositionHandler extends Handler
{
    public static function handle(?ImageAtPositionModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $module = ModuleModel::find($res->module_id);
        $module = ModuleHandler::handle($module);


        $position = PositionModel::find($res->position_id);
        $position = PositionHandler::handle($position);

        $res->position = $position;
        $res->module = $module;
        $res->__platform = get_value('business.platform' , $res->platform);

        $res->__path__ = empty($res->path) ? '' : FileUtil::url($res->path);

        return $res;
    }

}
