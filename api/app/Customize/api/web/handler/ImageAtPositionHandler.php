<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\handler\PositionHandler;
use App\Customize\api\web\model\ImageAtPositionModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\PositionModel;
use App\Customize\api\web\util\FileUtil;
use App\Customize\api\web\model\Model;
use stdClass;
use function core\convert_object;

class ImageAtPositionHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $position = PositionModel::find($res->position_id);
        $position = PositionHandler::handle($position);

        $res->module = $module;
        $res->position = $position;



        return $res;
    }

}
