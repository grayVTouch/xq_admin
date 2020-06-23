<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\ImageAtPositionHandler;
use App\Customize\api\web_v1\model\ImageAtPositionModel;
use App\Customize\api\web_v1\model\PositionModel;
use App\Http\Controllers\api\web_v1\Base;

class ImageAtPositionAction extends Action
{
    public static function imageAtPosition(Base $context , string $position ,  array $param = [])
    {
        $position = PositionModel::getByValue($position);
        if (empty($position)) {
            return self::error('位置不存在' , 404);
        }
        $res = ImageAtPositionModel::getByPositionId($position->id);
        $res = ImageAtPositionHandler::handleAll($res);
        return self::success($res);
    }
}
