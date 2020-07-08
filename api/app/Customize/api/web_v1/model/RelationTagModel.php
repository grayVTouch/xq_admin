<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RelationTagModel extends Model
{
    protected $table = 'xq_relation_tag';

    public static function getByRelationTypeAndRelationId(string $relation_type , int $relation_id): Collection
    {
        return self::where([
                    ['relation_type' , '=' , $relation_type] ,
                    ['relation_id' , '=' , $relation_id] ,
                ])
                ->get();
    }

    public static function delByRelationTypeAndRelationId(string $relation_type , int $relation_id): int
    {
        return self::where([
                    ['relation_type' , '=' , $relation_type] ,
                    ['relation_id' , '=' , $relation_id] ,
                ])
                ->delete();
    }

    public static function delByRelationTypeAndRelationIdAndTagId(string $relation_type , int $relation_id , int $tag_id): int
    {
        return self::where([
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
            ['tag_id' , '=' , $tag_id] ,
        ])->delete();
    }

    // 热门标签-返回给定数量
    public static function hotTagsByModuleIdAndRelationTypeAndLimit(int $module_id , string $relation_type , int $limit = 20): Collection
    {
        return self::select('*' , DB::raw('count(id) as total'))
            ->where([
                ['module_id' , '=' , $module_id] ,
                ['relation_type' , '=' , $relation_type] ,
            ])
            ->groupBy('tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 热门标签-分页
    public static function hotTagsWithPagerByValueAndModuleIdAndRelationTypeAndLimit(string $value , int $module_id , string $relation_type , int $limit = 20): Paginator
    {
        $value = strtolower($value);
        return self::select('*' , DB::raw('count(id) as total'))
            ->where([
                ['module_id' , '=' , $module_id] ,
                ['relation_type' , '=' , $relation_type] ,
            ])
            // like '%' and name like '%'
            ->whereRaw("lower(name) like concat('%','{$value}','%')")
            ->groupBy('tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }
}
