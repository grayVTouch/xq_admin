<?php


namespace App\Customize\api\web_v1\model;


use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VideoSubjectModel extends Model
{
    protected $table = 'xq_video_subject';

    public static function getNewestByFilterAndLimit(array $filter = [] , int $limit = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        return self::where($where)
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getHotByFilterAndLimit(array $filter = [] , int $limit = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        return self::selectRaw('vs.*, 
                (select sum(play_count) from xq_video where type = ? and video_subject_id = vs.id) as play_count ,
                (select sum(view_count) from xq_video where type = ? and video_subject_id = vs.id) as view_count , 
                (select sum(praise_count) from xq_video where type = ? and video_subject_id = vs.id) as praise_count ,
                (select sum(against_count) from xq_video where type = ? and video_subject_id = vs.id) as against_count
                ' , ['pro' , 'pro' , 'pro' , 'pro'])
            ->from('xq_video_subject as vs')
            ->where($where)
            ->orderBy('praise_count' , 'desc')
            ->orderBy('play_count' , 'desc')
            ->orderBy('view_count' , 'desc')
            ->orderBy('against_count' , 'asc')
            ->orderBy('vs.create_time' , 'desc')
            ->orderBy('vs.id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getHotWithPagerByFilterAndLimit(array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }
        return self::selectRaw('vs.*, 
                (select sum(play_count) from xq_video where type = ? and video_subject_id = vs.id) as play_count ,
                (select sum(view_count) from xq_video where type = ? and video_subject_id = vs.id) as view_count , 
                (select sum(praise_count) from xq_video where type = ? and video_subject_id = vs.id) as praise_count ,
                (select sum(against_count) from xq_video where type = ? and video_subject_id = vs.id) as against_count
                ' , ['pro' , 'pro' , 'pro' , 'pro'])
            ->from('xq_video_subject as vs')
            ->where($where)
            ->orderBy('praise_count' , 'desc')
            ->orderBy('play_count' , 'desc')
            ->orderBy('view_count' , 'desc')
            ->orderBy('against_count' , 'asc')
            ->orderBy('vs.create_time' , 'desc')
            ->orderBy('vs.id' , 'asc')
            ->paginate($limit);
    }


    public static function getByTagIdAndFilterAndLimit(int $tag_id , array $filter = [] , int $limit = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [
            ['vs.status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        return self::from('xq_video_subject as vs')
            ->select('vs.*')
            ->where($where)
            ->whereExists(function($query) use($tag_id){
                $query->from('xq_relation_tag')
                    ->where([
                        ['tag_id' , '=' , $tag_id] ,
                        ['relation_type' , '=' , 'video_subject'] ,
                    ])
                    ->whereRaw('vs.id = relation_id');
            })
            ->orderBy('vs.create_time' , 'desc')
            ->orderBy('vs.id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 标签对应的图片专题-非严格模式匹配
    public static function getByTagIdsAndFilterAndLimit(array $tag_ids = [] , array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        return self::from('xq_video_subject as vs')
            ->where($where)
            ->whereExists(function($query) use($tag_ids){
                $query->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'video_subject'] ,
                    ])
                    ->whereIn('tag_id' , $tag_ids)
                    ->whereRaw('vs.id = relation_id');
            })
            ->orderBy('vs.create_time' , 'desc')
            ->orderBy('vs.id' , 'asc')
            ->paginate($limit);
    }

    // 标签对应的图片专题-严格模式匹配
    public static function getInStrictByTagIdsAndFilterAndLimit(array $tag_ids = [] , array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        return self::from('xq_video_subject as vs')
            ->where($where)
            ->whereExists(function($query) use($tag_ids){
                $query->select('id')
                    ->selectRaw('count(id) as total')
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'video_subject'] ,
                    ])
                    ->whereIn('tag_id' , $tag_ids)
                    ->whereRaw('vs.id = relation_id')
                    ->groupBy('relation_id')
                    ->having('total' , '=' , count($tag_ids));
            })
            ->orderBy('vs.create_time' , 'desc')
            ->orderBy('vs.id' , 'asc')
            ->paginate($limit);
    }

    public static function getNewestWithPagerByFilterAndLimit(array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        return self::where($where)
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }

    public static function getWithPagerInStrictByFilterAndOrderAndLimit(array $filter = [] , $order = null , int $limit = 20)
    {
        $filter['value']        = $filter['value'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['tag_ids']      = $filter['tag_ids'] ?? [];

        $order = $order ?? ['field' => 'create_time' , 'value' => 'desc'];
        $value = strtolower($filter['value']);

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        $query = self::from('xq_video_subject as vs')
            ->where($where);

        return $query->whereRaw("lower(vs.name) like ?" , ["%{$value}%"])
            ->whereExists(function($query) use($filter){
                $query->select('id')
                    ->selectRaw('count(id) as total')
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'video_subject'] ,
                    ]);
                if (!empty($filter['tag_ids'])) {
                    $query->whereIn('tag_id' , $filter['tag_ids'])
                        ->groupBy('relation_id')
                        ->having('total' , '=' , count($filter['tag_ids']));
                }
                $query->whereRaw('relation_id = vs.id');
            })
            ->orderBy("vs.{$order['field']}" , $order['value'])
            ->orderBy('vs.id' , 'desc')
            ->paginate($limit);
    }

    public static function getWithPagerInLooseByFilterAndOrderAndLimit(array $filter = [] , $order = null , int $limit = 20)
    {
        $filter['value']        = $filter['value'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['tag_ids']      = $filter['tag_ids'] ?? [];

        $order = $order ?? ['field' => 'create_time' , 'value' => 'desc'];
        $value = strtolower($filter['value']);

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        $query = self::from('xq_video_subject as vs')
            ->where($where);

        return $query->whereRaw("lower(vs.name) like ?" , ["%{$value}%"])
            ->whereExists(function($query) use($filter){
                $query->select('id')
                    ->selectRaw('count(id) as total')
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'video_subject'] ,
                    ]);

                if (!empty($filter['tag_ids'])) {
                    $query->whereIn('tag_id' , $filter['tag_ids']);
                }

                $query->whereRaw('relation_id = vs.id');
            })
            ->orderBy("vs.{$order['field']}" , $order['value'])
            ->orderBy('vs.id' , 'desc')
            ->paginate($limit);
    }

    public static function countHandle(int $id , string $field , string $mode = '' , int $step = 1): int
    {
        $mode_range = ['increment' , 'decrement'];
        if (!in_array($mode , $mode_range)) {
            throw new Exception('不支持的操作模式，当前支持的模式有：' . implode(',' , $mode_range));
        }
        return self::where('id' , $id)->$mode($field , $step);
    }

    public static function recommendExcludeSelfByFilterAndLimit(int $self_id , array $filter = [] , int $limit = 20): Collection
    {
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['category_id']  = $filter['category_id'] ?? '';

        $where = [
            ['id' , '!=' , $self_id] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['vs.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['category_id'] !== '') {
            $where[] = ['vs.category_id' , '=' , $filter['category_id']];
        }

        return self::selectRaw('vs.*, 
                (select sum(play_count) from xq_video where type = ? and video_subject_id = vs.id) as play_count ,
                (select sum(view_count) from xq_video where type = ? and video_subject_id = vs.id) as view_count , 
                (select sum(praise_count) from xq_video where type = ? and video_subject_id = vs.id) as praise_count ,
                (select sum(against_count) from xq_video where type = ? and video_subject_id = vs.id) as against_count
                ' , ['pro' , 'pro' , 'pro' , 'pro'])
            ->from('xq_video_subject as vs')
            ->where($where)
            ->orderBy('praise_count' , 'desc')
            ->orderBy('play_count' , 'desc')
            ->orderBy('view_count' , 'desc')
            ->orderBy('against_count' , 'asc')
            ->orderBy('vs.create_time' , 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getByVideoSeriesIdAndExcludeVideoSubjectId(int $video_series_id , int $exclude_video_subject_id): Collection
    {
        return self::where([
                ['id' , '!=' , $exclude_video_subject_id] ,
                ['video_series_id' , '=' , $video_series_id] ,
            ])
            ->get();
    }
}
