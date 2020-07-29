<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class VideoCommentImageModel extends Model
{
    protected $table = 'xq_video_comment_image';

    public static function delByVideoId(int $video_id): int
    {
        return self::where('video_id' , $video_id)->delete();
    }
}
