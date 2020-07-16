<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\handler\ImageAtPositionHandler;
use App\Customize\api\web_v1\model\ImageAtPositionModel;
use App\Customize\api\web_v1\model\PositionModel;
use App\Http\Controllers\api\web_v1\Base;
use Illuminate\Support\Facades\Validator;

class ImageAtPositionAction extends Action
{
    public static function imageAtPosition(Base $context , string $position ,  array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $position = PositionModel::getByPlatformAndValue('web' , $position);
        if (empty($position)) {
            return self::error('位置不存在' , '' , 404);
        }
        $res = ImageAtPositionModel::getByModuleIdAndPositionId($module->id , $position->id);
        $res = ImageAtPositionHandler::handleAll($res);
        return self::success('' , $res);
    }
}
