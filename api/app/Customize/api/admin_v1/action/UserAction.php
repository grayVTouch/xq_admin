<?php


namespace App\Customize\api\admin_v1\action;



use App\Customize\api\admin_v1\handler\UserHandler;
use App\Customize\api\admin_v1\model\UserModel;
use App\Http\Controllers\api\admin_v1\Base;
use function api\admin_v1\user;

class UserAction extends Action
{
    public static function info(Base $context , array $param)
    {
        $user = user();
        return self::success([
            'user' => $user
        ]);
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = UserModel::search($value);
        $res = UserHandler::handleAll($res);
        return self::success($res);
    }
}
