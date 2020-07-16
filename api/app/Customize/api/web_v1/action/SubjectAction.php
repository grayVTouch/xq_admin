<?php


namespace App\Customize\api\web_v1\action;


use App\Customize\api\web_v1\handler\SubjectHandler;
use App\Customize\api\web_v1\model\SubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Http\Controllers\api\web_v1\Base;
use Illuminate\Support\Facades\Validator;

class SubjectAction extends Action
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
        $subject = SubjectModel::find($id);
        $subject = SubjectHandler::handle($subject);
        return self::success('' , $subject);
    }
}
