<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\handler\PositionHandler;
use App\Customize\api\web\model\ImageAtPositionModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\PositionModel;
use App\Customize\api\web\util\FileUtil;
use stdClass;
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
        ModuleHandler::handle($module);

        $position = PositionModel::find($res->position_id);
        $position = PositionHandler::handle($position);

        $res->module = $module;
        $res->position = $position;



        return $res;
    }

}
