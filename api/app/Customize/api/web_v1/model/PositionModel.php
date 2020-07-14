<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class PositionModel extends Model
{
    protected $table = 'xq_position';

    public static function getByPlatformAndValue(string $platform , string $value): ?PositionModel
    {
        return self::where([
                ['platform' , '=' , $platform] ,
                ['value' , '=' , $value] ,
            ])
            ->first();
    }
}
