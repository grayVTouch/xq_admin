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
    public static function hotTagsByRelationTypeAndFilterAndLimit(string $relation_type , array $filter = [] , int $limit = 20): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['rt.relation_type' , '=' , $relation_type] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['rt.module_id' , '=' , $filter['module_id']];
        }

        return self::from('xq_relation_tag as rt')
            ->select('rt.*' , DB::raw('count(rt.id) as total'))
            ->where($where)
            ->whereExists(function($query) use($relation_type , $filter){
                if ($relation_type === 'image_subject') {
                    $query->where('type' , $filter['type']);
                }
            })
            ->groupBy('rt.tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('rt.id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 热门标签-返回给定数量
    public static function hotTagsInImageSubjectByFilterAndLimit(array $filter = [] , int $limit = 20): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['rt.relation_type' , '=' , 'image_subject'] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['rt.module_id' , '=' , $filter['module_id']];
        }

        return self::from('xq_relation_tag as rt')
            ->select('rt.*' , DB::raw('count(rt.id) as total'))
            ->where($where)
            ->whereExists(function($query) use($filter){
                if ($filter['type'] !== '') {
                    $query->from('xq_image_subject')
                        ->where('type' , $filter['type'])
                        ->whereRaw('rt.relation_id = id');
                }
            })
            ->groupBy('rt.tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('rt.id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function hotTagsInVideoSubjectByFilterAndLimit(array $filter = [] , int $limit = 20): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [
            ['relation_type' , '=' , 'video_subject'] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        return self::select('*' , DB::raw('count(id) as total'))
            ->where($where)
            ->groupBy('tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 热门标签-分页
    public static function hotTagsWithPagerByValueAndRelationTypeAndFilterAndLimit(string $value , string $relation_type , array $filter = [] , int $limit = 20): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['rt.relation_type' , '=' , $relation_type] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['rt.module_id' , '=' , $filter['module_id']];
        }
        $value = strtolower($value);
        return self::select('*' , DB::raw('count(id) as total'))
            ->where($where)
            // like '%' and name like '%'
            ->whereRaw("lower(name) like ?" , "%{$value}%")
            ->groupBy('tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }

    // 热门标签-分页
    public static function hotTagsWithPagerInImageSubjectByValueAndFilterAndLimit(string $value , array $filter = [] , int $limit = 20): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['rt.relation_type' , '=' , 'image_subject'] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['rt.module_id' , '=' , $filter['module_id']];
        }

        $value = strtolower($value);
        return self::from('xq_relation_tag as rt')
            ->select('*' , DB::raw('count(rt.id) as total'))
            ->where($where)
            ->whereRaw("lower(rt.name) like ?" , ["%{$value}%"])
            ->whereExists(function($query) use($filter){
                if ($filter['type'] !== '') {
                    $query->from('xq_image_subject')
                        ->where('type' , $filter['type'])
                        ->whereRaw('rt.relation_id = id');
                }
            })
            ->groupBy('rt.tag_id')
            ->orderBy('total' , 'desc')
            ->orderBy('rt.id' , 'asc')
            ->paginate($limit);
    }
}
