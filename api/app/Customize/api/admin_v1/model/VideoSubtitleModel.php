<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VideoSubtitleModel extends Model
{
    protected $table = 'xq_video_subtitle';

    public static function delByVideoId(int $video_id): int
    {
        return self::where('video_id' , $video_id)->delete();
    }

    public static function getByVideoId(int $video_id): Collection
    {
        return self::where('video_id' , $video_id)->get();
    }

    public static function countByVideoId(int $video_id): int
    {
        return self::where('video_id' , $video_id)->count();
    }
}
