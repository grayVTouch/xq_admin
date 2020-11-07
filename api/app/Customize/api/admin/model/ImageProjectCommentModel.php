<?php


namespace App\Customize\api\admin\model;


class ImageProjectCommentModel extends Model
{
    protected $table = 'xq_image_project_comment';

    public static function delByImageProjectId(int $image_project_id): int
    {
        return self::where('image_project_id' , $image_project_id)->delete();
    }
}
