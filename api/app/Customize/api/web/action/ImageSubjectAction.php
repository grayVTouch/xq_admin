<?php


namespace App\Customize\api\web\action;


use App\Customize\api\web\handler\ImageSubjectHandler;
use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Http\Controllers\api\web\Base;
use Illuminate\Support\Facades\Validator;

class ImageSubjectAction extends Action
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
        $image_subject = ImageSubjectModel::find($id);
        $image_subject = ImageSubjectHandler::handle($image_subject);

        // 附加：图片专题
        ImageSubjectHandler::module($image_subject);

        return self::success('' , $image_subject);
    }
}
