<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ImageAtPositionModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\PositionModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\model\Model;
use stdClass;
use function api\admin\get_config_key_mapping_value;

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
        $module = ModuleHandler::handle($module);


        $position = PositionModel::find($res->position_id);
        $position = PositionHandler::handle($position);

        $res->position = $position;
        $res->module = $module;
        $res->__platform = get_config_key_mapping_value('business.platform' , $res->platform);



        return $res;
    }

}
