<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RegionModel;
use App\Customize\api\admin\model\VideoCompanyModel;
use App\Customize\api\admin\util\FileUtil;
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



        return $res;
    }

}
