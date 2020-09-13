<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class VideoModel extends Model
{
    protected $table = 'xq_video';

    public static function getByVideoSubjectId(int $video_subject_id): Collection
    {
        return self::where('video_subject_id' , $video_subject_id)
            ->orderBy('index' , 'asc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function sumPraiseCountByVideoSubjectId(int $video_subject_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_subject_id' , '=' , $video_subject_id] ,
            ])
            ->sum('praise_count');
    }

    public static function sumPlayCountByVideoSubjectId(int $video_subject_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_subject_id' , '=' , $video_subject_id] ,
            ])
            ->sum('play_count');
    }

    public static function sumViewCountByVideoSubjectId(int $video_subject_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_subject_id' , '=' , $video_subject_id] ,
            ])
            ->sum('view_count');
    }

    public static function sumAgainstCountByVideoSubjectId(int $video_subject_id): int
    {
        return (int) self::where([
                ['type' , '=' , 'pro'] ,
                ['video_subject_id' , '=' , $video_subject_id] ,
            ])
            ->sum('against_count');
    }

    public static function findByVideoSubjectIdAndMinIndexAndMaxIndex(int $video_subject_id , int $min , int $max): Collection
    {
        return self::where([
                ['video_subject_id' , '=' , $video_subject_id] ,
                ['index' , '>=' , $min] ,
                ['index' , '<=' , $max] ,
            ])
            ->get();
    }
}
