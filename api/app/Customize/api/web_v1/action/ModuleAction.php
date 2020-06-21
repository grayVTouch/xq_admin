<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\ModuleHandler;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Http\Controllers\api\web_v1\Base;

class ModuleAction extends Action
{
    public static function all(Base $context , array $param = [])
    {
        $res = ModuleModel::get();
        $res = ModuleHandler::handleAll($res);
        return self::success($res);
    }
}
