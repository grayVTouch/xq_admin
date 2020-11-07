<?php


namespace App\Customize\api\admin\model;



use Illuminate\Database\Eloquent\Collection;

class ImageProjectCommentImageModel extends Model
{
    protected $table = 'xq_image_project_comment_image';

    public static function delByImageProjectId(int $image_project_id): int
    {
        return self::where('image_project_id' , $image_project_id)->delete();
    }

    public static function getByImageProjectId(int $image_project_id): Collection
    {
        return self::where('image_project_id' , $image_project_id)->get();
    }
}
