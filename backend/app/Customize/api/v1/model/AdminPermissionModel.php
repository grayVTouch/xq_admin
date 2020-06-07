<?php


namespace App\Customize\api\v1\model;


use function core\convert_obj;

class AdminPermissionModel extends Model
{
    protected $table = 'xq_admin_permission';

    public static function getByRoleId(int $role_id)
    {
        $res = static::where('role_id' , $role_id)
            ->get();
        $res = convert_obj($res);
        return $res;
    }

    public static function getAll()
    {
        $res = static::orderBy('id' , 'asc')
            ->get();
        $res = convert_obj($res);
        return $res;
    }
}
