<?php


namespace App\Customize\api\web\action;

use App\Customize\api\web\handler\ModuleHandler;
use App\Customize\api\web\model\ModuleModel;
use App\Http\Controllers\api\web\Base;

class ModuleAction extends Action
{
    public static function all(Base $context , array $param = [])
    {
        $res = ModuleModel::getAll();
        $res = ModuleHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function default(Base $context , array $param = [])
    {
        $res = ModuleModel::getDefault();
        $res = ModuleHandler::handle($res);
        return self::success('' , $res);
    }
}
