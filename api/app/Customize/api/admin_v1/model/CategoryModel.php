<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Database\Eloquent\Collection;

class CategoryModel extends Model
{
    protected $table = 'xq_category';

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

    public static function getAllByModuleId(int $module_id): Collection
    {
        return self::where('module_id' , $module_id)
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

}
