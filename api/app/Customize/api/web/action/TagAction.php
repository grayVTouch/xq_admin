<?php


namespace App\Customize\api\web\action;


use App\Customize\api\web\handler\TagHandler;
use App\Customize\api\web\model\TagModel;
use App\Customize\api\web\model\ModuleModel;
use App\Http\Controllers\api\web\Base;
use Illuminate\Support\Facades\Validator;

class TagAction extends Action
{

    public static function show(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $subject = TagModel::find($id);
        $subject = TagHandler::handle($subject);
        return self::success('' , $subject);
    }
}
