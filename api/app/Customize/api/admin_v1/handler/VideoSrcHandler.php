<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;
use App\Customize\api\admin_v1\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoSrcHandler extends Handler
{
    public static function handle(?VideoSrcModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
//        $module = ModuleModel::find($res->module_id);
//        ModuleHandler::handle($module);

//        $res->module = $module;
        $res->__src__ = empty($res->src) ? '' : FileUtil::url($res->src);
        return $res;
    }

}
