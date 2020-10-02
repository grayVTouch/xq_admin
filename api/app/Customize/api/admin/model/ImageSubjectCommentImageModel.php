<?php


namespace App\Customize\api\admin\model;



use Illuminate\Database\Eloquent\Collection;

class ImageSubjectCommentImageModel extends Model
{
    protected $table = 'xq_image_subject_comment_image';

    public static function delByImageSubjectId(int $image_subject_id): int
    {
        return self::where('image_subject_id' , $image_subject_id)->delete();
    }

    public static function getByImageSubjectId(int $image_subject_id): Collection
    {
        return self::where('image_subject_id' , $image_subject_id)->get();
    }
}
