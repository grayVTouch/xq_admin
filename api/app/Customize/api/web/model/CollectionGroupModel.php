<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class CollectionGroupModel extends Model
{
    protected $table = 'xq_collection_group';

    public static function findByModuleIdAndUserIdAndName(int $module_id , int $user_id , string $name): ?CollectionGroupModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['name' , '=' , $name] ,
            ])->first();
    }

    public static function findByModuleIdAndUserIdAndNameExcludeIds(int $module_id , int $user_id , string $name , array $exclude_ids = []): ?CollectionGroupModel
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
                ['name' , '=' , $name] ,
            ])
            ->whereNotIn('id' , $exclude_ids)
            ->first();
    }


    public static function getByModuleIdAndUserIdAndValue(int $module_id , int $user_id , string $value = ''): Collection
    {

        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
                ['name' , 'like' , "%{$value}%"] ,
            ])
            ->get();
    }

    public static function getByModuleIdAndUserId(int $module_id , int $user_id): Collection
    {

        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
            ])
            ->orderBy('id' , 'desc')
            ->get();
    }

    public static function getByModuleIdAndUserIdAndRelationTypeAndValue(int $module_id , int $user_id , string $relation_type = '' , string $value = ''): Collection
    {
        $where = [
            ['cg.module_id' , '=' , $module_id] ,
            ['cg.user_id' , '=' , $user_id] ,
        ];
        $where[] = ['cg.name' , 'like' , "%{$value}%"];
        $query = self::from('xq_collection_group as cg')
            ->where($where);
        if (!empty($relation_type)) {
            $query->whereExists(function($query) use($relation_type){
                $query->select('id')
                    ->from('xq_collection')
                    ->whereRaw('collection_group_id = cg.id')
                    ->where('relation_type' , $relation_type);
            });
        }
        return $query
            ->orderBy('cg.created_at' , 'desc')
            ->get();
    }

    public static function getByModuleIdAndUserIdAndLimit(int $module_id , int $user_id , int $limit = 20): Collection
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
            ])
            ->limit($limit)
            ->get();
    }

    public static function countByModuleIdAndUserId(int $module_id , int $user_id): int
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
            ])
            ->count();
    }

    public static function delByModuleIdAndUserIdAndIds(int $module_id , int $user_id , array $ids)
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
        ])
            ->whereIn('id' , $ids)
            ->delete();
    }
}
