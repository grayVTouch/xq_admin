<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\NavHandler;
use App\Customize\api\web_v1\model\NavModel;
use App\Http\Controllers\api\web_v1\Base;
use Core\Lib\Category;
use function core\obj_to_array;

class NavAction extends Action
{
    public static function all(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $res = NavModel::getAllByModuleId($param['module_id']);
        $res = NavHandler::handleAll($res);
        $res = obj_to_array($res);
        $res = Category::childrens(0 , $res , null , false ,false);
        return self::success('' , $res);
    }
}
