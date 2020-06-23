<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class ImageAtPositionModel extends Model
{
    protected $table = 'xq_image_at_position';

    public static function getByPositionId(int $position_id): Collection
    {
        return self::where('position_id' , $position_id)->get();
    }
}
