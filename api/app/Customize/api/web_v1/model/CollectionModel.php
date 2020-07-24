<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

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

    public static function delByModuleIdAndUserIdAndId(int $module_id , int $user_id , int $id)
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['id' , '=' , $id] ,
        ])->delete();
    }

    public static function delByModuleIdAndUserIdAndCollectionGroupIds(int $module_id , int $user_id , array $collection_group_ids)
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
            ])
            ->whereIn('collection_group_id' , $collection_group_ids)
            ->delete();
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

    public static function countByCollectionGroupId(int $collection_group_id): int
    {
        return self::where([
            ['collection_group_id' , '=' , $collection_group_id] ,
        ])->count();
    }

    public static function countByCollectionGroupIdAndRelationType(int $collection_group_id , string $relation_type): int
    {
        return self::where([
            ['collection_group_id' , '=' , $collection_group_id] ,
            ['relation_type' , '=' , $relation_type] ,
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

    public static function getByModuleIdAndUserIdAndCollectionGroupIdAndLimit(int $module_id , string $user_id , int $collection_group_id , int $limit = 20): Collection
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
                ['collection_group_id' , '=' , $collection_group_id] ,
            ])
            ->limit($limit)
            ->get();
    }

    public static function getWithPagerByModuleIdAndUserIdAndCollectionGroupIdAndLimit(int $module_id , int $user_id , int $collection_group_id , string $relation_type = '' , int $limit = 20): Paginator
    {
        $where = [
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['collection_group_id' , '=' , $collection_group_id] ,
        ];
        if (!empty($relation_type)) {
            $where[] = ['relation_type' , '=' , $relation_type];
        }
        return self::where($where)
            ->orderBy('create_time' , 'desc')
            ->paginate($limit);
    }

    public static function countByUserId(int $user_id)
    {
        return self::where('user_id' , $user_id)->count();
    }

    public static function firstOrderIdAscByCollectionGroupId(int $collection_group_id): ?CollectionModel
    {
        return self::where('collection_group_id' , $collection_group_id)
            ->orderBy('id' , 'asc')
            ->first();
    }
}
