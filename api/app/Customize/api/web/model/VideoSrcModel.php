<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class VideoSrcModel extends Model
{
    protected $table = 'xq_video_src';

    public static function delByVideoId(int $video_id): int
    {
        return self::where('video_id' , $video_id)->delete();
    }

    public static function getByVideoId(int $video_id): Collection
    {
        return self::where('video_id' , $video_id)->get();
    }
}
