<?php


namespace App\Customize\api\admin_v1\model;


class AdminPermissionModel extends Model
{
    protected $table = 'xq_admin_permission';

    public static function getByRoleId(int $role_id)
    {
        $admin_permission_ids = RolePermissionPivot::getPermissionIdsByRoleId($role_id);
        return self::whereIn('id' , $admin_permission_ids)
            ->get();
    }

    public static function getAll()
    {
        return self::orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

}
