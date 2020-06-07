<?php


namespace App\Customize\api\v1\action;



use App\Http\Controllers\api\v1\Base;
use function api\v1\user;

class UserAction extends Action
{
    public static function info(Base $context , array $param)
    {
        $user = user();
        return self::success([
            'user' => $user
        ]);
    }
}
