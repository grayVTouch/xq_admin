<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class ImageSubjectModel extends Model
{
    protected $table = 'xq_image_subject';

    public static function getNewestByLimit(int $limit = 0): Collection
    {
        return self::where([
                ['status' , '=' , 1] ,
            ])
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }
}
