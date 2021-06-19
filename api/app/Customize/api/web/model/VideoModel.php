<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class VideoModel extends Model
{
    protected $table = 'xq_video';

    public static function getByVideoProjectId(int $video_project_id): Collection
    {
        return self::where('video_project_id' , $video_project_id)
            ->orderBy('index' , 'asc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function sumPraiseCountByVideoProjectId(int $video_project_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_project_id' , '=' , $video_project_id] ,
            ])
            ->sum('praise_count');
    }

    public static function sumPlayCountByVideoProjectId(int $video_project_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_project_id' , '=' , $video_project_id] ,
            ])
            ->sum('play_count');
    }

    public static function sumViewCountByVideoProjectId(int $video_project_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_project_id' , '=' , $video_project_id] ,
            ])
            ->sum('view_count');
    }

    public static function sumAgainstCountByVideoProjectId(int $video_project_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_project_id' , '=' , $video_project_id] ,
            ])
            ->sum('against_count');
    }

    public static function getByVideoProjectIdAndMinIndexAndMaxIndex(int $video_project_id , int $min , int $max): Collection
    {
        return self::where([
                ['video_project_id' , '=' , $video_project_id] ,
                ['index' , '>=' , $min] ,
                ['index' , '<=' , $max] ,
            ])
            ->orderBy('index' , 'asc')
            ->get();
    }
}
