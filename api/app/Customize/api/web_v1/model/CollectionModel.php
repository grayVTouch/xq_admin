<?php


namespace App\Customize\api\web_v1\model;


class CollectionModel extends Model
{
    protected $table = 'xq_collection';

    public static function delByModuleIdAndUserIdAndCollectionGroupIdAndRelationTypeAndRelationId(int $module_id , int $user_id , int $collection_group_id , string $relation_type , int $relation_id): int
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['collection_group_id' , '=' , $collection_group_id] ,
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
        ])->delete();
    }

    public static function findByModuleIdAndUserIdAndRelationTypeAndRelationId(int $module_id , int $user_id , string $relation_type , int $relation_id): ?CollectionModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
        ])->first();
    }

    public static function countByModuleIdAndUserIdAndCollectionGroupId(int $module_id , int $user_id , int $collection_group_id): int
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['collection_group_id' , '=' , $collection_group_id] ,
        ])->count();
    }

    public static function findByModuleIdAndUserIdAndCollectionGroupIdAndRelationTypeAndRelationId(int $module_id , int $user_id ,int $collection_group_id , string $relation_type , int $relation_id): ?CollectionModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['collection_group_id' , '=' , $collection_group_id] ,
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
        ])->first();
    }

    public static function countByModuleIdAndRelationTypeAndRelationId(int $module_id , string $relation_type , int $relation_id): int
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
        ])->count();
    }
}
