<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\RegionModel;
use App\Customize\api\web\model\VideoCompanyModel;
use App\Customize\api\web\util\FileUtil;
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
