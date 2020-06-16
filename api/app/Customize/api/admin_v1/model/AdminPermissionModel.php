<?php


namespace App\Customize\api\admin_v1\model;


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
        return self::orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

}
