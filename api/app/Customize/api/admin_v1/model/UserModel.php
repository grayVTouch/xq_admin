<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Database\Eloquent\Collection;

class UserModel extends Model
{
    protected $table = 'xq_user';

    public static function search(string $value = ''): Collection
    {
        return self::where('id' , $value)
            ->orWhere('username' , $value)
            ->orWhere('phone' , $value)
            ->orWhere('email' , $value)
            ->get();
    }
}
