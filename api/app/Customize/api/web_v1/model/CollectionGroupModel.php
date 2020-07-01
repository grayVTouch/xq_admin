<?php


namespace App\Customize\api\web_v1\model;


class CollectionGroupModel extends Model
{
    protected $table = 'xq_collection_group';

    public static function isExistsByModuleIdAndUserIdAndName(int $module_id , int $user_id , string $name)
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['name' , '=' , $name] ,
            ])->count() > 0;
    }
}
