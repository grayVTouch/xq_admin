<?php


namespace App\Customize\api\web_v1\model;


class CategoryModel extends Model
{
    protected $table = 'xq_category';

    public static function getByRoleId(int $role_id)
    {
        return self::where('role_id' , $role_id)
            ->get();
    }

    public static function getAllByModuleId(int $module_id)
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['enable' , '=' , 1]
            ])
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

}
