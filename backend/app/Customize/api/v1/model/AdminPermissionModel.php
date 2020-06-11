<?php


namespace App\Customize\api\v1\model;


class AdminPermissionModel extends Model
{
    protected $table = 'xq_admin_permission';

    public static function getByRoleId(int $role_id)
    {
        return self::where('role_id' , $role_id)
            ->get();
    }

    public static function getAll()
    {
        return self::where([
                ['enable' , '='  , 1] ,
            ])
            ->orderBy('id' , 'asc')
            ->get();
    }
}
