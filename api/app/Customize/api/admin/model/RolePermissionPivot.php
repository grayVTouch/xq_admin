<?php


namespace App\Customize\api\admin\model;


use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;

class RolePermissionPivot extends Pivot
{
    protected $table = 'xq_role_permission';

    public static function delByRoleId(int $role_id) :int
    {
        return self::where('role_id' , $role_id)
            ->delete();
    }

    public static function delByRoleIds(array $role_ids = []) :int
    {
        return self::whereIn('role_id' , $role_ids)
            ->delete();
    }

    public static function getPermissionIdsByRoleId(int $role_id): array
    {
        return self::where('role_id' , $role_id)
            ->pluck('admin_permission_id')
            ->toArray();
    }

}
