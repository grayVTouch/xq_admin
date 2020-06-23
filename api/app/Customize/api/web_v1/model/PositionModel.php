<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class PositionModel extends Model
{
    protected $table = 'xq_position';

    public static function getByValue(string $value): ?PositionModel
    {
        return self::where('value' , $value)->first();
    }
}
