<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\CategoryHandler;
use App\Customize\api\web_v1\model\CategoryModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Http\Controllers\api\web_v1\Base;
use Core\Lib\Category;
use Illuminate\Support\Facades\Validator;
use function core\obj_to_array;

class CategoryAction extends Action
{
    public static function all(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $res = CategoryModel::getAllByModuleId($param['module_id']);
        $res = CategoryHandler::handleAll($res);
        $res = obj_to_array($res);
        $res = Category::childrens(0 , $res , null , false ,false);
        return self::success('' , $res);
    }

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
        $category = CategoryModel::find($id);
        $category = CategoryHandler::handle($category);
        return self::success('' , $category);
    }
}
