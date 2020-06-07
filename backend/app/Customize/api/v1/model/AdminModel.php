<?php


namespace App\Customize\api\v1\model;


use function core\convert_obj;

class AdminModel extends Model
{
    protected $table = 'xq_admin';

    public static function findByUsername(string $username = '')
    {
        $res = static::where('username' , $username)->first();
        if (empty($res)) {
            return ;
        }
        $res = convert_obj($res);
        return $res;
    }
}
