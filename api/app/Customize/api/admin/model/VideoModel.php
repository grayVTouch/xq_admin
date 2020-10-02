<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class VideoModel extends Model
{
    protected $table = 'xq_video';

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id']           = $filter['id'] ?? '';
        $filter['name']         = $filter['name'] ?? '';
        $filter['user_id']      = $filter['user_id'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['category_id']          = $filter['category_id'] ?? '';
        $filter['video_subject_id']     = $filter['video_subject_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';
        $filter['status']       = $filter['status'] ?? '';

        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';

        $where = [];

        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }

        if ($filter['name'] !== '') {
            $where[] = ['name' , 'like' , "%{$filter['name']}%"];
        }

        if ($filter['user_id'] !== '') {
            $where[] = ['user_id' , '=' , $filter['user_id']];
        }

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['category_id'] !== '') {
            $where[] = ['category_id' , '=' , $filter['category_id']];
        }

        if ($filter['video_subject_id'] !== '') {
            $where[] = ['video_subject_id' , '=' , $filter['video_subject_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        if ($filter['status'] !== '') {
            $where[] = ['status' , '=' , $filter['status']];
        }

        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($limit);
    }

    public static function countByDateAndProcessStatus(string $date , int $process_status): int
    {
        return self::whereRaw('date_format(created_at , "%Y-%m-%d") = ?' , $date)
            ->where('process_status' , $process_status)
            ->count();
    }

    public static function countByProcessStatus(int $process_status): int
    {
        return self::where('process_status' , $process_status)
            ->count();
    }

    // 检查剧集是否已经存在
    public static function findByVideoSubjectIdAndIndex(int $video_subject_id , int $index): ?VideoModel
    {
        return self::where([
            ['type' , '=' , 'pro'] ,
            ['video_subject_id' , '=' , $video_subject_id] ,
            ['index' , '=' , $index] ,
        ])->first();
    }

    public static function findExcludeSelfByVideoIdAndVideoSubjectIdAndIndex(int $video_id , int $video_subject_id , int $index): ?VideoModel
    {
        return self::where([
            ['type' , '=' , 'pro'] ,
            ['id' , '!=' , $video_id] ,
            ['video_subject_id' , '=' , $video_id] ,
            ['index' , '=' , $index] ,
        ])->first();
    }
}
