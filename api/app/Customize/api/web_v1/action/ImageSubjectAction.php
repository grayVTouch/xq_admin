<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\ImageSubjectHandler;
use App\Customize\api\web_v1\model\ImageSubjectModel;
use App\Http\Controllers\api\web_v1\Base;
use function api\web_v1\my_config;

class ImageSubjectAction extends Action
{
    public static function getNewestByLimit(Base $context , int $limit , array $param = [])
    {
        $limit = empty($limit) ? my_config('app.limit') : $limit;
        $res = ImageSubjectModel::getNewestByLimit($limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success($res);
    }
}
