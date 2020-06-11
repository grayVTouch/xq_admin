<?php


namespace App\Customize\api\v1\model;


use function core\convert_obj;

class AdminTokenModel extends Model
{
    protected $table = 'xq_admin_token';

    public static function findByToken(string $token = '')
    {
        return self::where('token' , $token)
            ->first();
    }
}
