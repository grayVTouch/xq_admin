<?php


namespace App\Customize\api\admin\model;


use function core\convert_object;

class AdminTokenModel extends Model
{
    protected $table = 'xq_admin_token';

    public static function findByToken(string $token = '')
    {
        return self::where('token' , $token)
            ->first();
    }
}
