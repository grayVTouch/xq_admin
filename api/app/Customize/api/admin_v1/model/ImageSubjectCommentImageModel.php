<?php


namespace App\Customize\api\admin_v1\model;


class ImageSubjectCommentImageModel extends Model
{
    protected $table = 'xq_image_subject_comment_image';

    public static function delByImageSubjectId(int $image_subject_id): int
    {
        return self::where('image_subject_id' , $image_subject_id)->delete();
    }
}
