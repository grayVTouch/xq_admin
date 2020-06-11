<?php


namespace App\Customize\api\v1\model;

class AdminModel extends Model
{
    protected $table = 'xq_admin';

    public static function findByUsername(string $username = '')
    {
        return self::where('username' , $username)
            ->first();
    }
}
