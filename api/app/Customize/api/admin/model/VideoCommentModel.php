<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class VideoCommentModel extends Model
{
    protected $table = 'xq_video_comment';

    public static function delByVideoId(int $video_id): int
    {
        return self::where('video_id' , $video_id)->delete();
    }
}
