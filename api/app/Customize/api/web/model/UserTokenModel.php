<?php


namespace App\Customize\api\web\model;


class UserTokenModel extends Model
{
    protected $table = 'xq_user_token';

    public static function findByToken(string $token): ?UserTokenModel
    {
        return self::where('token' , $token)->first();
    }
}
