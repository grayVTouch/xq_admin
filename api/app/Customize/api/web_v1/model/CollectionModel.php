<?php


namespace App\Customize\api\web_v1\model;


class CollectionModel extends Model
{
    protected $table = 'xq_collection';

    public static function delByModuleIdAndUserIdAndRelationTableAndRelationId(int $module_id , int $user_id , string $relation_table , int $relation_id): int
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['relation_table' , '=' , $relation_table] ,
            ['relation_id' , '=' , $relation_id] ,
        ])->delete();
    }
}
