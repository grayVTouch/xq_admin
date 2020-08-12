<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\RegionModel;
use App\Customize\api\admin_v1\model\VideoCompanyModel;
use App\Customize\api\admin_v1\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoCompanyHandler extends Handler
{
    public static function handle(?VideoCompanyModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $region = RegionModel::find($res->country_id);
        $region = RegionHandler::handle($region);

        $res->module = $module;
        $res->region = $region;

        $res->__thumb__ = empty($res->thumb) ? '' : FileUtil::url($res->thumb);

        return $res;
    }

}
