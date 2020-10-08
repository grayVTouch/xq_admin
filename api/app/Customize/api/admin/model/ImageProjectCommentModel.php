<?php


namespace App\Customize\api\admin\model;


class ImageProjectCommentModel extends Model
{
    protected $table = 'xq_image_subject_comment';

    public static function delByImageSubjectId(int $image_subject_id): int
    {
        return self::where('image_subject_id' , $image_subject_id)->delete();
    }
}
