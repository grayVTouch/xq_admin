<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RelationTagModel extends Model
{
    protected $table = 'xq_relation_tag';

    public static function getByRelationTableAndRelationId(string $relation_table , int $relation_id): Collection
    {
        return self::where([
                    ['relation_table' , '=' , $relation_table] ,
                    ['relation_id' , '=' , $relation_id] ,
                ])
                ->get();
    }

    public static function delByRelationTableAndRelationId(string $relation_table , int $relation_id): int
    {
        return self::where([
                    ['relation_table' , '=' , $relation_table] ,
                    ['relation_id' , '=' , $relation_id] ,
                ])
                ->delete();
    }

    public static function delByRelationTableAndRelationIdAndTagId(string $relation_table , int $relation_id , int $tag_id): int
    {
        return self::where([
            ['relation_table' , '=' , $relation_table] ,
            ['relation_id' , '=' , $relation_id] ,
            ['tag_id' , '=' , $tag_id] ,
        ])->delete();
    }

    // 热门标签-返回给定数量
    public static function hotTagsByModuleIdAndRelationTableAndLimit(int $module_id , string $relation_table , int $limit = 20): Collection
    {
        return self::select('*' , DB::raw('count(id) as total'))
            ->where([
                ['module_id' , '=' , $module_id] ,
                ['relation_table' , '=' , $relation_table] ,
            ])
            ->groupBy('tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 热门标签-分页
    public static function hotTagsWithPagerByModuleIdAndRelationTableAndLimit(int $module_id , string $relation_table , int $limit = 20): Paginator
    {
        return self::select('*' , DB::raw('count(id) as total'))
            ->where([
                ['module_id' , '=' , $module_id] ,
                ['relation_table' , '=' , $relation_table] ,
            ])
            ->groupBy('tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }
}
